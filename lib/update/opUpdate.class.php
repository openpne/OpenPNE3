<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opUpdate provides updating
 *
 * @package    OpenPNE
 * @subpackage update
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opUpdate extends Doctrine_Migration
{
  protected
    $dbManager = null,
    $database = null,
    $connection = null,
    $doctrineProcess = null;

  public function __construct($dbManager, $name = '')
  {
    $this->dbManager = $dbManager;
    if (!$name)
    {
      $names = $this->dbManager->getNames();
      $name = array_shift($names);
    }
    $this->database = $this->dbManager->getDatabase($name);

    $params = $this->database->getParameterHolder()->getAll();
    unset($params['classname']);
    $doctrine = new sfDoctrineDatabase($params);

    $this->connection = $doctrine->getDoctrineConnection();
    $this->doctrineProcess = new opUpdateDoctrineMigrationProcess($this->connection);
  }

  abstract public function update();

  public function preUpdate()
  {
  }

  public function postUpdate()
  {
  }

  public function doUpdate()
  {
    $this->preUpdate();
    $this->update();
    $this->postUpdate();

    foreach ($this->_changes as $type => $changes)
    {
      if ($changes)
      {
        $method = 'process'.Doctrine_Inflector::classify($type);
        $this->doctrineProcess->$method($changes);
      }
    }
  }
}
