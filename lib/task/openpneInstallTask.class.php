<?php

class openpneInstallTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'install';

    $this->addArguments(array(
      new sfCommandArgument('dsn', sfCommandArgument::REQUIRED, 'The database dsn'),
    ));

    $this->briefDescription = 'Install OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:install|INFO] task installs and configures OpenPNE.
Call it with:

  [./symfony openpne:install|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->clearCache();
    $this->configureDatabase($arguments['dsn']);
    $this->buildDb();
    $this->clearCache();
  }

  protected function configureDatabase($dsn)
  {
    $file = sfConfig::get('sf_config_dir').'/databases.yml';
    $config = array();

    if (file_exists($file))
    {
      $config = sfYaml::load($file);
    }

    $config['all']['propel'] = array(
      'class' => 'sfPropelDatabase',
      'param' => array('dsn' => $dsn, 'encoding' => 'utf8'),
    );

    file_put_contents($file, sfYaml::dump($config, 4));

    // update propel.ini
    $propelini = sfConfig::get('sf_config_dir').'/propel.ini';
    if (file_exists($propelini))
    {
      $content = file_get_contents($propelini);
      if (preg_match('/^(.+?):\/\//', $dsn, $match))
      {
        $content = preg_replace('/^propel\.database(\s*)=(\s*)(.+?)$/m', 'propel.database$1=$2'.$match[1], $content);
        $content = preg_replace('/^propel\.database\.createUrl(\s*)=(\s*)(.+?)$/m', 'propel.database.createUrl$1=$2'.$dsn, $content);
        $content = preg_replace('/^propel\.database\.url(\s*)=(\s*)(.+?)$/m', 'propel.database.url$1=$2'.$dsn, $content);

        file_put_contents($propelini, $content);
      }
    }
  }

  protected function clearCache()
  {
    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }

  protected function buildDb()
  {
    $this->schemaToXML(self::DO_NOT_CHECK_SCHEMA, 'generated-');
    $this->copyXmlSchemaFromPlugins('generated-');

    $buildAllLoad = new sfPropelBuildAllLoadTask($this->dispatcher, $this->formatter);
    $buildAllLoad->run(array('application' => 'pc_frontend'));
  }
}
