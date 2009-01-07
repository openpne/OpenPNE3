<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

    while (
      !($dbname = $this->ask('Type database name'))
    );

    $sock = '';
    if ($dbms == 'mysql' && $hostname == 'localhost') {
      $sock = $this->ask('Type database socket path (optional)');
    }

    $maskedPassword = '******';
    if (!$password)
    {
      $maskedPassword = '';
    }

    $this->log($this->formatList(array(
      'The DBMS             ' => $dbms,
      'The Database Username' => $username,
      'The Database Password' => $maskedPassword,
      'The Database Hostname' => $hostname,
      'The Database Name    ' => $dbname,
      'The Database Socket  ' => $sock,
    )));

    if ($this->askConfirmation('Is it OK to start this task? (y/n)'))
    {
      @$this->fixPerms();
      @$this->clearCache();
      $this->configureDatabase($dbms, $username, $password, $hostname, $dbname, $sock);
      $this->buildDb();
      $this->publishAssets();
      $this->clearCache();
    }
  }

  protected function createDSN($dbms, $hostname, $dbname, $sock)
  {
    $result = $dbms.':';

    $data = array();

    if ($dbname)
    {
      $data[] = 'dbname='.$dbname;
    }

    if ($hostname)
    {
      $data[] = 'hostname='.$hostname;
    }

    if ($sock)
    {
      $data[] = 'unix_socket='.$sock;
    }

    $result .= implode(';', $data);
    return $result;
  }

  protected function configureDatabase($dbms, $username, $password, $hostname, $dbname, $sock)
  {
    $dsn = $this->createDSN($dbms, $hostname, $dbname, $sock);

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
    $buildAllLoad = new sfPropelBuildAllLoadTask($this->dispatcher, $this->formatter);
    $buildAllLoad->run(array(), array('--no-confirmation'));
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
