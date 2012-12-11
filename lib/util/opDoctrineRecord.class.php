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
 /**
  * UNDEFINED_DATETIME
  *
  * It is used as NULL in a datetime column by getter and setter.
  * We can't use real NULL value for this use, because NULL is ambiguous among RDBMSs.
  *
  * MySQL prepares "0000-00-00 00:00:00" for this use. But it can't be used in PostgreSQL.
  * PostgreSQL's behavior is like ISO 8601. It accepts "0000-01-01" (it is the year 1 B.C.).
  * In definition of Standard SQL, TIMESTAMP accepts "0001-01-01 00:00:00".
  *
  * OpenPNE will support many type of RDBMS. So we should select acceptable format of every RDBMSs.
  * As you can see, it is "0001-01-01 00:00:00".
  *
  * I referred to hnw's entry: http://openlab.dino.co.jp/2007/11/10/170436147.html [ja]
  */
  const UNDEFINED_DATETIME = '0001-01-01 00:00:00';

 /**
  * UNDEFINED_DATETIME_BC
  */
  const UNDEFINED_DATETIME_BC = '0000-00-00 00:00:00';

  const MAX_NESTING_LEVEL = 50;

  // TODO: Move it to good place (e.g. create new "opDoctrine" class? read like: opDoctrine::ATTR_4BYTES_UTF8_READY)
  const ATTR_4BYTES_UTF8_READY = 999;

  protected
    $roleList = array(),

    $nestingLevelCheckClasses = array(
      'Album',
    ),

    $nested = array();

  public function save(Doctrine_Connection $conn = null)
  {
    $hasConnection = Doctrine_Manager::getInstance()
        ->hasConnectionForComponent($this->getTable()->getComponentName());

    if (is_null($conn) && !$hasConnection)
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

  protected function checkIsDatetimeField($fieldName)
  {
    $definition = $this->_table->getColumnDefinition($fieldName);

    return 'datetime' === $definition['type'];
  }

  protected function checkIsNonBinaryStringField($fieldName)
  {
    $definition = $this->_table->getColumnDefinition($fieldName);

    return in_array($definition['type'], array(
      'string', 'array', 'object', 'clob', 'char', 'varchar',
    ), true);
  }

  protected function replace4BytesUtf8Characters($value)
  {
    if (is_array($value))
    {
      $result = array();
      foreach ($value as $k => $v)
      {
        $result[$this->replace4BytesUtf8Characters($k)] = $this->replace4BytesUtf8Characters($v);
      }

      return $result;
    }

    if (!is_string($value))
    {
      return $value;
    }

    // See: RFC 3629 (section 4, Syntax of UTF-8 Byte Sequences)
    // http://tools.ietf.org/html/rfc3629#section-4
    return preg_replace('/(
      \xF0[\x90-\xBF][\x80-\xBF]{2}| # %xF0 %x90-BF 2( UTF8-tail )
      [\xF1-\xF3][\x80-\xBF]{3}|     # %xF1-F3 3( UTF8-tail )
      \xF4[\x80-\x8F][\x80-\xBF]{2}  # %xF4 %x80-8F 2( UTF8-tail )
    )/x', "\xEF\xBF\xBD", $value);
  }

  protected function isReadyFor4BytesUtf8()
  {
    $conn = $this->_table->getConnection();
    if ($conn->getAttribute(self::ATTR_4BYTES_UTF8_READY))
    {
      return true;
    }

    return !($conn instanceof Doctrine_Connection_MySQL);
  }

  protected function _set($fieldName, $value, $load = true)
  {
    // In setter, empty value must be handled as opDoctrineRecord::UNDEFINED_DATETIME
    if ($this->checkIsDatetimeField($fieldName) && empty($value))
    {
      $value = self::UNDEFINED_DATETIME;
    }

    $definition = $this->_table->getColumnDefinition($fieldName);

    // "utf8", a type of character set in MySQL, can't handle 4 bytes utf8 characters
    // so we replace such a character to "U+FFFD" (A unicode "REPLACEMENT CHARACTER").
    if (!$this->isReadyFor4BytesUtf8())
    {
      if ($this->checkIsNonBinaryStringField($fieldName))
      {
        $value = $this->replace4BytesUtf8Characters($value);
      }
    }

    return parent::_set($fieldName, $value, $load);
  }

  public function get($fieldName, $load = true)
  {
    if (!in_array(get_class($this), $this->nestingLevelCheckClasses))
    {
      return parent::get($fieldName, $load);
    }

    if (empty($this->nested[$fieldName]))
    {
      $this->nested[$fieldName] = 0;
    }
    $this->nested[$fieldName]++;

    if ($this->nested[$fieldName] > self::MAX_NESTING_LEVEL)
    {
      return $this->_get($fieldName, $load);
    }

    return parent::get($fieldName, $load);
  }

  public function _get($fieldName, $load = true)
  {
    $value = parent::_get($fieldName, $load);

    // In getter, opDoctrineRecord::UNDEFINED_DATETIME must be handled as null
    if ($this->checkIsDatetimeField($fieldName) && in_array($value, array(self::UNDEFINED_DATETIME, self::UNDEFINED_DATETIME_BC), true))
    {
      $value = null;
    }

    return $value;
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

    $this->addListener(new opDoctrineEventNotifier());
  }

  public function setTableName($tableName)
  {
    if (sfConfig::get('op_table_prefix'))
    {
      $tableName = sfConfig::get('op_table_prefix').$tableName;
    }

    parent::setTableName($tableName);
  }
}
