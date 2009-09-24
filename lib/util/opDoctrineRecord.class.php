<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineRecord
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opDoctrineRecord extends sfDoctrineRecord implements Zend_Acl_Resource_Interface
{
  protected $roleList = array();

  public function save(Doctrine_Connection $conn = null)
  {
    if (is_null($conn))
    {
      $conn = opDoctrineQuery::chooseConnection(true);
    }

    parent::save($conn);
  }

  public function hasColumn($name, $type = null, $length = null, $options = array())
  {
    // Temporary coping with the problem reported in http://trac.symfony-project.org/ticket/6873
    if ('string' === $type && is_null($length))
    {
      $length = 2147483647;
    }

    return parent::hasColumn($name, $type, $length, $options);
  }

  public function getResourceId()
  {
    $tableName = $this->getTable()->getTableName();
    $identifier = array_values($this->identifier());
    $identifier = array_shift($identifier);

    return $tableName.'.'.$identifier;
  }

  public function getRoleId(Member $member)
  {
    $this->checkReadyForAcl();

    if (empty($this->roleList[$member->id]))
    {
      $this->roleList[$member->id] = $this->generateRoleId($member);
    }

    return $this->roleList[$member->id];
  }

  public function clearRoleList()
  {
    $this->checkReadyForAcl();

    $this->roleList = array();
  }

  public function isAllowed(Member $member, $privilege)
  {
    $this->checkReadyForAcl();

    $acl = $this->getTable()->getAcl($this);

    return $acl->isAllowed($this->getRoleId($member), $this, $privilege);
  }

  public function checkReadyForAcl()
  {
    if (!($this instanceof opAccessControlRecordInterface))
    {
      throw new LogicException(sprintf('%s must implement the opAccessControlRecordInterface for access controll.', get_class($this)));
    }

    if (!($this->getTable() instanceof opAccessControlDoctrineTable))
    {
      throw new LogicException(sprintf('%s must be subclass of the opAccessControlDoctrineTable for access controll.', get_class($this->getTable())));
    }
  }

  public function setUp()
  {
    parent::setUp();

    if (!($this->getTable()->getConnection()->getAttribute(Doctrine::ATTR_EXPORT) & Doctrine::EXPORT_CONSTRAINTS))
    {
      $this->addListener(new opApplicationLevelCascadingListener());
    }
  }
}
