<?php

class opPluginDeactivateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'deactivate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [opPlugin:deactivate|INFO] task deactivates an installed plugin.
Call it with:

  [./symfony opPlugin:deactivate opSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $configuration = $this->createConfiguration('pc_frontend', 'cli');
    $name = $arguments['name'];

    if (!$configuration->isPluginExists($name))
    {
      throw new sfException(sprintf('Plugin "%s" does not installed', $name));
    }

    if ($configuration->isDisabledPlugin($name))
    {
      throw new sfException(sprintf('Plugin "%s" is already disactivated', $name));
    }

    $file = sfConfig::get('sf_config_dir').'/plugin.yml';
    $config = array('activation' => array());

    if (file_exists($file))
    {
      $config = array_merge($config, sfYaml::load($file));
    }

    $config['activation'][$name] = false;

    file_put_contents($file, sfYaml::dump($config, 4));

    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }
}
