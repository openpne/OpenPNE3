<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMigration provides way to migrate
 *
 * @package    OpenPNE
 * @subpackage migration
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMigration extends Doctrine_Migration
{
  protected
    $migrationBase = null,

    $dispatcher = null,
    $dbManager = null,
    $pluginInstance = null,

    $targetName = '',
    $connectionName = '';

 /**
  * Constructor
  */
  public function __construct($dispatcher, $dbManager, $targetName = '', $connectionName = '')
  {
    $this->migrationBase = new opMigrationBase();

    $this->dispatcher = $dispatcher;
    $this->dbManager = $dbManager;
    $this->setConnectionName($connectionName);
    $this->database = $this->dbManager->getDatabase($this->connectionName);
    $this->initializeDatabaseConfiguration();

    $this->setTargetName($targetName);

    $this->setFormatter();
    return parent::__construct($this->getMigrationScriptDirectory());
  }

 /**
  * Sets name of connection to the database
  *
  * @param string $name
  */
  protected function setConnectionName($name = '')
  {
    $this->connectionName = $name;
    if (!$this->connectionName)
    {
      $names = $this->dbManager->getNames();
      $this->connectionName = array_shift($names);
    }
  }

 /**
  * Initializes database confiigurations
  */
  protected function initializeDatabaseConfiguration()
  {
    $params = $this->database->getParameterHolder()->getAll();
    unset($params['classname']);
    $doctrine = new sfDoctrineDatabase($params);

    $this->connection = $doctrine->getDoctrineConnection();
    $this->connection->getManager()->setAttribute(Doctrine::ATTR_IDXNAME_FORMAT, '%s');
    $this->connection->getManager()->setAttribute(Doctrine::ATTR_QUOTE_IDENTIFIER, true);
    $this->doctrineProcess = new opDoctrineMigrationProcess($this->connection);
  }

 /**
  * Sets formatter
  */
  protected function setFormatter()
  {
    $this->formatter = new sfFormatter();
    if ('cli' === PHP_SAPI)
    {
      $this->formatter = new sfAnsiColorFormatter();
    }
  }

 /**
  * Sets a name of the target of this migration
  */
  protected function setTargetName($name)
  {
    if ($name && $name !== 'OpenPNE')
    {
      $this->targetName = $name;
      $this->pluginInstance = opPlugin::getInstance($this->targetName, $this->dispatcher);
    }
    else
    {
      $this->targetName = 'OpenPNE';
    }
  }

  protected function _doMigrateStep($direction, $num)
  {
    parent::_doMigrateStep($direction, $num);

    $file = $this->getMigrationFixtureFile($num);
    if ($file)
    {
      Doctrine::loadData($file, true);
    }
  }

 /**
  * doMigrate
  *
  * @see Doctrine_Migration
  */
  protected function doMigrate($direction)
  {
    $method = 'pre'.ucfirst($direction);
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

    $method = 'post'.ucfirst($direction);
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
  public function setCurrentVersion($number)
  {
    Doctrine::getTable('SnsConfig')->set($this->targetName.'_revision', $number);
  }

 /**
  * @see Doctrine_Migration
  */
  public function getCurrentVersion()
  {
    return Doctrine::getTable('SnsConfig')->get($this->targetName.'_revision', 0);
  }

 /**
  * @see Doctrine_Migration
  */
  public function hasMigrated()
  {
    return is_null(Doctrine::getTable('SnsConfig')->get($this->targetName.'_revision'));
  }

 /**
  * @see Doctrine_Migration
  */
  public function getMigrationClass($num)
  {
    foreach ($this->_migrationClasses as $classMigrationNum => $className)
    {
      if ($classMigrationNum == $num)
      {
        return new $className($this->dispatcher, $this->dbManager, $this->targetName, $this->connectionName);
      }
    }
    throw new Doctrine_Migration_Exception('Could not find migration class for migration step: '.$num);
  }

 /**
  * Gets a migration script directory
  *
  * @return string
  */
  protected function getMigrationScriptDirectory()
  {
    if ($this->targetName === 'OpenPNE')
    {
      $dir = sfConfig::get('sf_data_dir').'/migrations';
    }
    else
    {
      $dir = sfConfig::get('sf_plugins_dir').'/'.$this->targetName.'/data/migrations';
    }

    if (!is_readable($dir))
    {
      return null;
    }

    return $dir;
  }

 /**
  * Get a migration fixture file
  *
  * @return string
  */
  protected function getMigrationFixtureFile($num)
  {
    $dir = $this->getMigrationScriptDirectory();
    if (!$dir)
    {
      return false;
    }

    $result = sfFinder::type('file')->name(str_pad($num, 3, '0', STR_PAD_LEFT).'_*.yml')->in($dir);
    if ($result)
    {
      return array_shift($result);
    }

    return false;
  }

  public function hasMigrationScriptDirectory()
  {
    return (!is_null($this->getMigrationScriptDirectory()));
  }

  public function loadMigrationClass($name, $path = null)
  {
    // ignores generated directory
    if (!is_null($path) && 'generated' === basename(dirname($path)))
    {
      return false;
    }

    return parent::loadMigrationClass($name, $path);
  }

  public function getVersion()
  {
    return $this->getSourceVersion();
  }

  public function getSourceVersion()
  {
    if ($this->pluginInstance instanceof opPlugin)
    {
      return $this->pluginInstance->getVersion();
    }

    return OPENPNE_VERSION;
  }

  public function getVersionByRevision($revision)
  {
    $version = '';

    foreach ((array)$this->_migrationClassesDirectory as $dir)
    {
      $files = sfFinder::type('file')->name('/^0*'.$revision.'_.*\.php$/')->in($dir);
      if (!empty($files))
      {
        $version = basename(dirname(array_shift($files)));
        break;
      }
    }

    return $version;
  }

  public function getRevisionByVersion($version, $directory)
  {
    $revision = 0;

    $currentVersion = $this->getVersionByRevision($this->getCurrentVersion());

    $direction = 'up';
    if (version_compare($currentVersion, $version, '>'))
    {
      $direction = 'down';
    }

    $files = sfFinder::type('file')->name('*.php')->prune('generated')->in($directory);
    $iterator = new CompareMigrateDirectoryVersionFilterIterator($files, str_replace('-dev', '', $version), $direction);
    $targets = array();
    foreach ($iterator as $file)
    {
      $targets[] = basename($file);
    }

    if ($targets)
    {
      sort($targets);
      $target = $targets[0];
      if ($direction === 'up')
      {
        $target = $targets[count($targets) - 1];
      }

      $pos = strpos($target, '_');
      $revision = (int)substr($target, 0, $pos);
    }

    return $revision;
  }


  public function __call($name, $arguments)
  {
    if (method_exists($this->migrationBase, $name))
    {
      return call_user_func_array(array($this->migrationBase, $name), $arguments);
    }

    throw new BadMethodCallException('Undefined method '.get_class($this).'::'.$name.'()');
  }

  public function __get($name)
  {
    return $this->migrationBase->getProperty($name);
  }

  public function __set($name, $value)
  {
    $this->migrationBase->setProperty($name, $value);
  }
}

class CompareMigrateDirectoryVersionFilterIterator extends FilterIterator
{
  protected
    $limitVersion = null,
    $direction    = 'up';

  public function __construct(array $fileList, $limitVersion, $direction = '')
  {
    $this->limitVersion = $limitVersion;
    if ($direction)
    {
      $this->direction = $direction;
    }

    return parent::__construct(new ArrayIterator($fileList));
  }

  public function accept()
  {
    $version = basename(dirname($this->current()));
    $operand = '<=';
    if ($this->direction === 'down')
    {
      $operand = '<';
    }
    return version_compare($version, $this->limitVersion, $operand);
  }
}
