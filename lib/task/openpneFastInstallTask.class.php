<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpne:fast-install task. enables one-liner install.
 *
 * @auther Hiromi Hishida <info@77-web.com>
 */

class openpneFastInstallTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'fast-install';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('dbms', null, sfCommandOption::PARAMETER_OPTIONAL, 'The dbms for database connection. mysql or sqlite', 'mysql'),
      new sfCommandOption('dbuser', null, sfCommandOption::PARAMETER_OPTIONAL, 'A username for database connection.'),
      new sfCommandOption('dbpassword', null, sfCommandOption::PARAMETER_OPTIONAL, 'A password for database connection.'),
      new sfCommandOption('dbhost', null, sfCommandOption::PARAMETER_OPTIONAL, 'A hostname for database connection.'),
      new sfCommandOption('dbport', null, sfCommandOption::PARAMETER_OPTIONAL, 'A port number for database conection.'),
      new sfCommandOption('dbname', null, sfCommandOption::PARAMETER_REQUIRED, 'A database name for database connection.'),
      new sfCommandOption('dbsock', null, sfCommandOption::PARAMETER_OPTIONAL, 'A database socket path for database connection.'),
      new sfCommandOption('internet', null, sfCommandOption::PARAMETER_NONE, 'Connect Internet Option to download plugins list.'),
    ));

    $this->briefDescription = 'Install OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:fast-install] task installs and configures OpenPNE.
Call it with:

  [./symfony openpne:fast-install --dbms=mysql --dbuser=your-username --dbpassword=your-password --dbname=your-dbname --dbhost=localhost --internet]

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $dbms = $options['dbms'];
    $username = $options['dbuser'];
    $password = $options['dbpassword'];
    $hostname = $options['dbhost'];
    $dbname = $options['dbname'];
    $port = $options['dbport'];
    $sock = $options['dbsock'];
    
    if (empty($dbms))
    {
      $this->logSection('installer', 'task aborted: empty dbms');

      return 1;
    }

    if (empty($dbname))
    {
      $this->logSection('installer', 'task aborted: empty dbname');

      return 1;
    }

    if ('sqlite' !== $dbms)
    {
      if(empty($username))
      {
        $this->logSection('installer', 'task aborted: dbuser is empty');

        return 1;
      }
      
      if(empty($hostname))
      {
        $hostname = '127.0.0.1';
      }
    }
    else
    {
      $dbname = realpath(dirname($dbname)).DIRECTORY_SEPARATOR.basename($dbname);
    }
    
    unset($options['dbms'], $options['dbuser'], $options['dbpassword'], $options['dbname'], $options['dbhost'], $options['dbport'], $options['dbsock']);
    $this->doInstall($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options);

    if ('sqlite' === $dbms)
    {
      $this->getFilesystem()->chmod($dbname, 0666);
    }

    $this->publishAssets();

    // _PEAR_call_destructors() causes an E_STRICT error
    error_reporting(error_reporting() & ~E_STRICT);

    $this->logSection('installer', 'installation is completed!');
  }

  protected function doInstall($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options)
  {
    if ($options['internet'])
    {
      $this->installPlugins();
    }
    else
    {
      new opPluginManager($this->dispatcher, null, null);
    }
    @$this->fixPerms();
    @$this->clearCache();
    $this->configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options);
    $this->buildDb($options);
  }

  protected function createDSN($dbms, $hostname, $port, $dbname, $sock)
  {
    $result = $dbms.':';

    $data = array();

    if ($dbname)
    {
      if ('sqlite' === $dbms)
      {
        $data[] = $dbname;
      }
      else
      {
        $data[] = 'dbname='.$dbname;
      }
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

  protected function configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options)
  {
    $dsn = $this->createDSN($dbms, $hostname, $port, $dbname, $sock);

    $file = sfConfig::get('sf_config_dir').'/databases.yml';
    $config = array();

    if (file_exists($file))
    {
      $config = sfYaml::load($file);
    }

    $env = 'all';
    if ('prod' !== $options['env'])
    {
      $env = $options['env'];
    }

    $config[$env]['doctrine'] = array(
      'class' => 'sfDoctrineDatabase',
      'param' => array(
        'dsn'        => $dsn,
        'username'   => $username,
        'encoding'   => 'utf8',
        'attributes' => array(
           Doctrine::ATTR_USE_DQL_CALLBACKS => true,
        ),
      ),
    );

    if ($password)
    {
      $config[$env]['doctrine']['param']['password'] = $password;
    }

    file_put_contents($file, sfYaml::dump($config, 4));
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

  protected function buildDb($options)
  {
    $tmpdir = sfConfig::get('sf_data_dir').'/fixtures_tmp';
    $this->getFilesystem()->mkdirs($tmpdir);
    $this->getFilesystem()->remove(sfFinder::type('file')->in(array($tmpdir)));

    $pluginDirs = sfFinder::type('dir')->name('data')->in(sfFinder::type('dir')->name('op*Plugin')->maxdepth(1)->in(sfConfig::get('sf_plugins_dir')));
    $fixturesDirs = sfFinder::type('dir')->name('fixtures')
      ->prune('migrations', 'upgrade')
      ->in(array_merge(array(sfConfig::get('sf_data_dir')), $this->configuration->getPluginSubPaths('/data'), $pluginDirs));
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

    $task = new sfDoctrineBuildTask($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);
    $task->setConfiguration($this->configuration);
    $task->run(array(), array(
      'no-confirmation' => true,
      'db'              => true,
      'model'           => true,
      'forms'           => true,
      'filters'         => true,
      'sql'             => true,
      'and-load'        => $tmpdir,
      'application'     => $options['application'],
      'env'             => $options['env'],
    ));

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

}
