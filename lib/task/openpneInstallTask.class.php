<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneInstallTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'install';

    $this->briefDescription = 'Install OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:install|INFO] task installs and configures OpenPNE.
Call it with:

  [./symfony openpne:install|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    while (
      !($dbms = $this->ask('Choose DBMS (mysql, pgsql or sqlite)'))
      || !in_array($dbms, array('mysql', 'pgsql', 'sqlite'))
    );

    while (
      !($username = $this->ask('Type database username'))
    );

    $password = $this->ask('Type database password (optional)');

    while (
      !($hostname = $this->ask('Type database hostname'))
    );

    $port = $this->ask('Type database port number (optional)');

    while (
      !($dbname = $this->ask('Type database name'))
    );

    $sock = '';
    if ($dbms == 'mysql' && ($hostname == 'localhost' || $hostname == '127.0.0.1')) {
      $sock = $this->ask('Type database socket path (optional)');
    }

    $maskedPassword = '******';
    if (!$password)
    {
      $maskedPassword = '';
    }

    $this->log($this->formatList(array(
      'The DBMS                 ' => $dbms,
      'The Database Username    ' => $username,
      'The Database Password    ' => $maskedPassword,
      'The Database Hostname    ' => $hostname,
      'The Database Port Number ' => $port,
      'The Database Name        ' => $dbname,
      'The Database Socket      ' => $sock,
    )));

    if ($this->askConfirmation('Is it OK to start this task? (y/n)'))
    {
      $this->installPlugins();
      @$this->fixPerms();
      @$this->clearCache();
      $this->configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock);
      $this->buildDb();
      $this->publishAssets();
      $this->clearCache();

      // _PEAR_call_destructors() causes an E_STRICT error
      error_reporting(error_reporting() & ~E_STRICT);
    }
  }

  protected function createDSN($dbms, $hostname, $port, $dbname, $sock)
  {
    $result = $dbms.':';

    $data = array();

    if ($dbname)
    {
      $data[] = 'dbname='.$dbname;
    }

    if ($hostname)
    {
      $data[] = 'host='.$hostname;
    }

    if ($port)
    {
      $data[] = 'port='.$port;
    }

    if ($sock)
    {
      $data[] = 'unix_socket='.$sock;
    }

    $result .= implode(';', $data);
    return $result;
  }

  protected function configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock)
  {
    $dsn = $this->createDSN($dbms, $hostname, $port, $dbname, $sock);

    $file = sfConfig::get('sf_config_dir').'/databases.yml';
    $config = array('dev' => array('propel' => array('param' => array(
      'classname' => 'DebugPDO',
    ))));

    if (file_exists($file))
    {
      $config = sfYaml::load($file);
    }

    $config['all']['propel'] = array(
      'class' => 'sfPropelDatabase',
      'param' => array(
        'dsn'        => $dsn,
        'username'   => $username,
        'encoding'   => 'utf8',
        'classname'  => 'PropelPDO',
        'persistent' => true,
        'pooling'    => true,
      ),
    );

    if ($password)
    {
      $config['all']['propel']['param']['password'] = $password;
    }

    file_put_contents($file, sfYaml::dump($config, 4));

    // update propel.ini
    $propelini = sfConfig::get('sf_config_dir').'/propel.ini';


    if (!file_exists($propelini)) {
      copy($propelini.'.sample', $propelini);
    }

    $content = file_get_contents($propelini);
    $content = preg_replace('/^propel\.database(\s*)=(\s*)(.+?)$/m', 'propel.database$1=$2'.$dbms, $content);
    $content = preg_replace('/^propel\.driver(\s*)=(\s*)(.+?)$/m', 'propel.driver$1=$2'.$dbms, $content);
    $content = preg_replace('/^propel\.database\.url(\s*)=(\s*)(.+?)$/m', 'propel.database.url$1=$2'.$dsn, $content);
    $content = preg_replace('/^propel\.user(\s*)=(\s*)(.+?)$/m', 'propel.user$1=$2'.$username, $content);
    $content = preg_replace('/^propel\.password(\s*)=(\s*)(.+?)$/m', 'propel.password$1=$2'.$password, $content);

    file_put_contents($propelini, $content);
  }

  protected function clearCache()
  {
    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }

  protected function publishAssets()
  {
    $publishAssets = new sfPluginPublishAssetsTask($this->dispatcher, $this->formatter);
    $publishAssets->run();
  }

  protected function buildDb()
  {
    $tmpdir = sfConfig::get('sf_data_dir').'/fixtures_tmp';
    $this->getFilesystem()->mkdirs($tmpdir);
    $this->getFilesystem()->remove(sfFinder::type('file')->in(array($tmpdir)));
    
    $fixturesDirs = sfFinder::type('dir')->name('fixtures')->in(array_merge(array(sfConfig::get('sf_data_dir')), $this->configuration->getPluginSubPaths('/data')));
    $i = 0;
    foreach ($fixturesDirs as $fixturesDir)
    {
      $files = sfFinder::type('file')->name('*.yml')->sort_by_name()->in(array($fixturesDir));
      
      foreach ($files as $file)
      {
        $this->getFilesystem()->copy($file, $tmpdir.'/'.sprintf('%03d_%s_%s.yml', $i, basename($file, '.yml'), md5(uniqid(rand(), true))));
      }
      $i++;
    }

    $buildAllLoad = new sfPropelBuildAllLoadTask($this->dispatcher, $this->formatter);
    $buildAllLoad->run(array(), array('--no-confirmation', '--dir='.$tmpdir));

    $this->getFilesystem()->remove(sfFinder::type('file')->in(array($tmpdir)));
    $this->getFilesystem()->remove($tmpdir);
  }

  protected function installPlugins()
  {
    $task = new opPluginSyncTask($this->dispatcher, $this->formatter);
    $task->run();
  }

  protected function fixPerms()
  {
    $permissions = new openpnePermissionTask($this->dispatcher, $this->formatter);
    $permissions->run();
  }

  protected function formatList($list)
  {
    $result = '';

    foreach ($list as $key => $value)
    {
      $result .= $this->formatter->format($key, 'INFO')."\t";
      $result .= $value."\n";
    }

    return $result;
  }
}
