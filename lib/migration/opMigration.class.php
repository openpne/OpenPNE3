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
abstract class opMigration extends Doctrine_Migration
{
  protected $targetName = '';

 /**
  * Constructor
  */
  public function __construct($dispatcher, $dbManager, $pluginName = '', $connectionName = '')
  {
    $this->dispatcher = $dispatcher;
    $this->dbManager = $dbManager;
    if (!$connectionName)
    {
      $names = $this->dbManager->getNames();
      $connectionName = array_shift($connectionName);
    }
    $this->database = $this->dbManager->getDatabase($connectionName);

    $params = $this->database->getParameterHolder()->getAll();
    unset($params['classname']);
    $doctrine = new sfDoctrineDatabase($params);

    $this->connection = $doctrine->getDoctrineConnection();
    $this->doctrineProcess = new opUpdateDoctrineMigrationProcess($this->connection);

    if ($pluginName)
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
}
