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

class openpneFastInstallTask extends openpneInstallTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'fast-install';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('dbms', 'mysql', sfCommandOption::PARAMETER_OPTIONAL, 'The dbms for database connection. mysql or sqlite'),
      new sfCommandOption('dbuser', null, sfCommandOption::PARAMETER_OPTIONAL, 'A username for database connection.'),
      new sfCommandOption('dbpassword', null, sfCommandOption::PARAMETER_OPTIONAL, 'A password for database connection.'),
      new sfCommandOption('dbhost', null, sfCommandOption::PARAMETER_OPTIONAL, 'A hostname for database connection.'),
      new sfCommandOption('dbport', null, sfCommandOption::PARAMETER_OPTIONAL, 'A port number for database conection.'),
      new sfCommandOption('dbname', null, sfCommandOption::PARAMETER_REQUIRED, 'A database name for database connection.'),
      new sfCommandOption('dbsock', null, sfCommandOption::PARAMETER_OPTIONAL, 'A database socket path for database connection.'),
      new sfCommandOption('internet', null, sfCommandOption::PARAMETER_NONE, 'Connect Internet Option to download plugins list.'),
      new sfCommandOption('non-recreate-db', null, sfCommandOption::PARAMETER_NONE, 'Non recreate DB'),
    ));

    $this->briefDescription = 'Install OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:fast-install] task installs and configures OpenPNE.
Call it with:

  [./symfony openpne:fast-install --dbms=mysql --dbuser=your-username --dbpassword=your-password --dbname=your-dbname --dbhost=localhost --internet --non-recreate-db]

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

    if ($dbms !== 'sqlite')
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
    $this->configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options);
    if ($options['internet'])
    {
      $this->installPlugins();
    }
    @$this->fixPerms();
    @$this->clearCache();
    $this->buildDb($options);
  }


  protected function configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options)
  {
    parent::configureDatabase($dbms, $username, $password, $hostname, $port, $dbname, $sock, $options);
    
    sfConfig::set('sf_use_database', true);
    sfContext::getInstance()->set('databaseManager', new sfDatabaseManager($this->configuration));
  }
}
