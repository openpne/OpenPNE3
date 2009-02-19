<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMigration provides migration
 *
 * @package    OpenPNE
 * @subpackage migration
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMigration extends Doctrine_Migration
{
  protected
    $dispatcher = null,
    $dbManager = null,
    $targetName = '',
    $connectionName = '';

 /**
  * Constructor
  */
  public function __construct($dispatcher, $dbManager, $pluginName = '', $connectionName = '')
  {
    $this->dispatcher = $dispatcher;
    $this->dbManager = $dbManager;
    $this->connectionName = $connectionName;
    if (!$this->connectionName)
    {
      $names = $this->dbManager->getNames();
      $this->connectionName = array_shift($names);
    }
    $this->database = $this->dbManager->getDatabase($this->connectionName);

    $params = $this->database->getParameterHolder()->getAll();
    unset($params['classname']);
    $doctrine = new sfDoctrineDatabase($params);

    $this->connection = $doctrine->getDoctrineConnection();
    $this->connection->getManager()->setAttribute(Doctrine::ATTR_IDXNAME_FORMAT, '%s');
    $this->doctrineProcess = new opUpdateDoctrineMigrationProcess($this->connection);

    if ($pluginName && $pluginName !== 'OpenPNE')
    {
      $this->targetName = $pluginName;
      $directory = sfConfig::get('sf_plugins_dir').'/'.$pluginName.'/data/migrations';
    }
    else
    {
      $this->targetName = 'OpenPNE';
      $directory = sfConfig::get('sf_data_dir').'/migrations';
    }

    $this->formatter = new sfFormatter();
    if ('cli' === PHP_SAPI)
    {
      $this->formatter = new sfAnsiColorFormatter();
    }

    return parent::__construct($directory);
  }

 /**
  * doMigrate
  *
  * @see Doctrine_Migration
  */
  protected function doMigrate($direction)
  {
    $method = 'pre'.$direction;
    $this->$method();

    if (method_exists($this, $direction))
    {
      $this->$direction();

      foreach ($this->_changes as $type => $changes)
      {
        $process = $this->doctrineProcess;
        $funcName = 'process'.Doctrine_Inflector::classify($type);

        if (!empty($changes))
        {
          $process->$funcName($changes);
        }
      }
    }

    $method = 'post'.$direction;
    $this->$method();
  }

 /**
  * @see Doctrine_Migration
  */
  protected function createMigrationTable()
  {
    // do nothing
  }

 /**
  * @see Doctrine_Migration
  */
  protected function setCurrentVersion($number)
  {
    SnsConfigPeer::set($this->targetName.'_revision', $number);
  }

 /**
  * @see Doctrine_Migration
  */
  public function getCurrentVersion()
  {
    return SnsConfigPeer::get($this->targetName.'_revision', 0);
  }

 /**
  * @see Doctrine_Migration
  */
  public function hasMigrated()
  {
    return is_null(SnsConfigPeer::get($this->targetName.'_revision'));
  }

 /**
  * @see Doctrine_Migration
  */
  protected function getMigrationClass($num)
  {
    foreach ($this->_migrationClasses as $classMigrationNum => $info)
    {
      $className = $info['className'];

      if ($classMigrationNum == $num)
      {
        return new $className($this->dispatcher, $this->dbManager, $this->targetName, $this->connectionName);
      }
    }

    throw new Doctrine_Migration_Exception('Could not find migration class for migration step: '.$num);
  }

 /**
  * @see Doctrine_Migration
  */
  public function loadMigrationClassesFromDirectory()
  {
    $classes = get_declared_classes();

    foreach ((array)$this->_migrationClassesDirectory as $dir)
    {
      $files = sfFinder::type('file')->name('*.php')->in($dir);
      $iterator = new CompareMigrateDirectoryVersionFilterIterator($files, str_replace('-dev', '', OPENPNE_VERSION));
      foreach ($iterator as $file)
      {
        if (!in_array(basename($file), $this->_loadedMigrations))
        {
          require_once $file;

          $requiredClass = array_diff(get_declared_classes(), $classes);
          $requiredClass = end($requiredClass);
          if ($requiredClass)
          {
            $this->_loadedMigrations[$requiredClass] = basename($file);
          }
        }
      }
    }
  }
}

class CompareMigrateDirectoryVersionFilterIterator extends FilterIterator
{
  protected $limitVersion;

  public function __construct(array $fileList, $limitVersion)
  {
    $this->limitVersion = $limitVersion;

    return parent::__construct(new ArrayIterator($fileList));
  }

  public function accept()
  {
    $version = basename(dirname($this->current()));
    return version_compare($version, $this->limitVersion, '<=');
  }
}
