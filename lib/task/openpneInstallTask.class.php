<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneInstallTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'install';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
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
    $username = '';
    $password = '';
    $hostname = '';
    $port = '';
    $sock = '';
    $maskedPassword = '******';

    $validator = new sfValidatorCallback(array('required' => true, 'callback' => array($this, 'validateDBMS')));
    $dbms = $this->askAndValidate(array('Choose DBMS:', '- mysql', '- pgsql (unsupported)', '- sqlite (unsupported)'), $validator, array('style' => 'QUESTION_LARGE'));
    if (!$dbms)
    {
      $this->logSection('installer', 'task aborted');

      return 1;
    }

    if ($dbms !== 'sqlite')
    {
      $username = $this->askAndValidate(array('Type database username'), new opValidatorString(), array('style' => 'QUESTION_LARGE'));
      $password = $this->askAndValidate(array('Type database password (optional)'), new opValidatorString(array('required' => false)), array('style' => 'QUESTION_LARGE'));
      $hostname = $this->askAndValidate(array('Type database hostname'), new opValidatorString(), array('style' => 'QUESTION_LARGE'));
      $port = $this->askAndValidate(array('Type database port number (optional)'), new sfValidatorInteger(array('required' => false)), array('style' => 'QUESTION_LARGE'));
    }

    $dbname = $this->askAndValidate(array('Type database name'), new opValidatorString(), array('style' => 'QUESTION_LARGE'));
    if ($dbms == 'sqlite')
    {
      $dbname = realpath(dirname($dbname)).DIRECTORY_SEPARATOR.basename($dbname);
    }

    if ($dbms == 'mysql' && ($hostname == 'localhost' || $hostname == '127.0.0.1'))
    {
      $sock = $this->askAndValidate(array('Type database socket path (optional)'), new opValidatorString(array('required' => false)), array('style' => 'QUESTION_LARGE'));
    }

    if (!$password)
    {
      $maskedPassword = '';
    }

    $list = array(
      'The DBMS                 : '.$dbms,
      'The Database Username    : '.$username,
      'The Database Password    : '.$maskedPassword,
      'The Database Hostname    : '.$hostname,
      'The Database Port Number : '.$port,
      'The Database Name        : '.$dbname,
      'The Database Socket      : '.$sock,
    );

    if (!$this->askConfirmation(array_merge($list, array('', 'Is it OK to start this task? (Y/n)')), 'QUESTION_LARGE'))
    {
      $this->logSection('installer', 'task aborted');

      return 1;
    }

    $this->doInstall($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options);

    if ($dbms === 'sqlite')
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
    $this->installPlugins();
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
      if ($dbms === 'sqlite')
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

  public function validateDBMS($validator, $value, $arguments = array())
  {
    $list = array('mysql', 'pgsql', 'sqlite');
    if (!in_array($value, $list))
    {
      throw new sfValidatorError($validator, 'You must specify "mysql", "pgsql" or "sqlite"');
    }

    if ('mysql' !== $value)
    {
      if ($this->askConfirmation(array(
        '===================',
        ' WARNING',
        '===================',
        $value.' is UNSUPPORTED by this version of OpenPNE!',
        '',
        'DO NOT use this DBMS, unless you are expert at this DBMS and you can cope some troubles.',
        'If you want to give us some feedback about this DBMS, please visit: http://redmine.openpne.jp/',
        '',
        'Do you give up using this DBMS? (Y/n)',
        ), 'ERROR_LARGE', true)
      )
      {
        return false;
      }
    }

    return $value;
  }
}
