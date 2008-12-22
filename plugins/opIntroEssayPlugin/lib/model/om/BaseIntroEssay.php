<?php


abstract class BaseIntroEssay extends BaseObject  implements Persistent {


  const PEER = 'IntroEssayPeer';

	
	protected static $peer;

	
	protected $id;

	
	protected $from_id;

	
	protected $to_id;

	
	protected $content;

	
	protected $updated_at;

	
	protected $aMemberRelatedByFromId;

	
	protected $aMemberRelatedByToId;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	
	public function applyDefaultValues()
	{
	}

	
	public function getId()
	{
		return $this->id;
	}

	
	public function getFromId()
	{
		return $this->from_id;
	}

	
	public function getToId()
	{
		return $this->to_id;
	}

	
	public function getContent()
	{
		return $this->content;
	}

	
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->updated_at === null) {
			return null;
		}


		if ($this->updated_at === '0000-00-00 00:00:00') {
									return null;
		} else {
			try {
				$dt = new DateTime($this->updated_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
			}
		}

		if ($format === null) {
						return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = IntroEssayPeer::ID;
		}

		return $this;
	} 
	
	public function setFromId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->from_id !== $v) {
			$this->from_id = $v;
			$this->modifiedColumns[] = IntroEssayPeer::FROM_ID;
		}

		if ($this->aMemberRelatedByFromId !== null && $this->aMemberRelatedByFromId->getId() !== $v) {
			$this->aMemberRelatedByFromId = null;
		}

		return $this;
	} 
	
	public function setToId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->to_id !== $v) {
			$this->to_id = $v;
			$this->modifiedColumns[] = IntroEssayPeer::TO_ID;
		}

		if ($this->aMemberRelatedByToId !== null && $this->aMemberRelatedByToId->getId() !== $v) {
			$this->aMemberRelatedByToId = null;
		}

		return $this;
	} 
	
	public function setContent($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->content !== $v) {
			$this->content = $v;
			$this->modifiedColumns[] = IntroEssayPeer::CONTENT;
		}

		return $this;
	} 
	
	public function setUpdatedAt($v)
	{
						if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
									try {
				if (is_numeric($v)) { 					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
															$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->updated_at !== null || $dt !== null ) {
			
			$currNorm = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) 					)
			{
				$this->updated_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = IntroEssayPeer::UPDATED_AT;
			}
		} 
		return $this;
	} 
	
	public function hasOnlyDefaultValues()
	{
						if (array_diff($this->modifiedColumns, array())) {
				return false;
			}

				return true;
	} 
	
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->from_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->to_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->content = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->updated_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating IntroEssay object", $e);
		}
	}

	
	public function ensureConsistency()
	{

		if ($this->aMemberRelatedByFromId !== null && $this->from_id !== $this->aMemberRelatedByFromId->getId()) {
			$this->aMemberRelatedByFromId = null;
		}
		if ($this->aMemberRelatedByToId !== null && $this->to_id !== $this->aMemberRelatedByToId->getId()) {
			$this->aMemberRelatedByToId = null;
		}
	} 
	
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(IntroEssayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

				
		$stmt = IntroEssayPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); 
		if ($deep) {  
			$this->aMemberRelatedByFromId = null;
			$this->aMemberRelatedByToId = null;
		} 	}

	
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(IntroEssayPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			IntroEssayPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	public function save(PropelPDO $con = null)
	{
    if ($this->isModified() && !$this->isColumnModified(IntroEssayPeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(IntroEssayPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$affectedRows = $this->doSave($con);
			$con->commit();
			IntroEssayPeer::addInstanceToPool($this);
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

												
			if ($this->aMemberRelatedByFromId !== null) {
				if ($this->aMemberRelatedByFromId->isModified() || $this->aMemberRelatedByFromId->isNew()) {
					$affectedRows += $this->aMemberRelatedByFromId->save($con);
				}
				$this->setMemberRelatedByFromId($this->aMemberRelatedByFromId);
			}

			if ($this->aMemberRelatedByToId !== null) {
				if ($this->aMemberRelatedByToId->isModified() || $this->aMemberRelatedByToId->isNew()) {
					$affectedRows += $this->aMemberRelatedByToId->save($con);
				}
				$this->setMemberRelatedByToId($this->aMemberRelatedByToId);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = IntroEssayPeer::ID;
			}

						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = IntroEssayPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += IntroEssayPeer::doUpdate($this, $con);
				}

				$this->resetModified(); 			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


												
			if ($this->aMemberRelatedByFromId !== null) {
				if (!$this->aMemberRelatedByFromId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMemberRelatedByFromId->getValidationFailures());
				}
			}

			if ($this->aMemberRelatedByToId !== null) {
				if (!$this->aMemberRelatedByToId->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMemberRelatedByToId->getValidationFailures());
				}
			}


			if (($retval = IntroEssayPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = IntroEssayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getFromId();
				break;
			case 2:
				return $this->getToId();
				break;
			case 3:
				return $this->getContent();
				break;
			case 4:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = IntroEssayPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getFromId(),
			$keys[2] => $this->getToId(),
			$keys[3] => $this->getContent(),
			$keys[4] => $this->getUpdatedAt(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = IntroEssayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setFromId($value);
				break;
			case 2:
				$this->setToId($value);
				break;
			case 3:
				$this->setContent($value);
				break;
			case 4:
				$this->setUpdatedAt($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = IntroEssayPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setFromId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setToId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setContent($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(IntroEssayPeer::DATABASE_NAME);

		if ($this->isColumnModified(IntroEssayPeer::ID)) $criteria->add(IntroEssayPeer::ID, $this->id);
		if ($this->isColumnModified(IntroEssayPeer::FROM_ID)) $criteria->add(IntroEssayPeer::FROM_ID, $this->from_id);
		if ($this->isColumnModified(IntroEssayPeer::TO_ID)) $criteria->add(IntroEssayPeer::TO_ID, $this->to_id);
		if ($this->isColumnModified(IntroEssayPeer::CONTENT)) $criteria->add(IntroEssayPeer::CONTENT, $this->content);
		if ($this->isColumnModified(IntroEssayPeer::UPDATED_AT)) $criteria->add(IntroEssayPeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(IntroEssayPeer::DATABASE_NAME);

		$criteria->add(IntroEssayPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setFromId($this->from_id);

		$copyObj->setToId($this->to_id);

		$copyObj->setContent($this->content);

		$copyObj->setUpdatedAt($this->updated_at);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new IntroEssayPeer();
		}
		return self::$peer;
	}

	
	public function setMemberRelatedByFromId(Member $v = null)
	{
		if ($v === null) {
			$this->setFromId(NULL);
		} else {
			$this->setFromId($v->getId());
		}

		$this->aMemberRelatedByFromId = $v;

						if ($v !== null) {
			$v->addIntroEssayRelatedByFromId($this);
		}

		return $this;
	}


	
	public function getMemberRelatedByFromId(PropelPDO $con = null)
	{
		if ($this->aMemberRelatedByFromId === null && ($this->from_id !== null)) {
			$c = new Criteria(MemberPeer::DATABASE_NAME);
			$c->add(MemberPeer::ID, $this->from_id);
			$this->aMemberRelatedByFromId = MemberPeer::doSelectOne($c, $con);
			
		}
		return $this->aMemberRelatedByFromId;
	}

	
	public function setMemberRelatedByToId(Member $v = null)
	{
		if ($v === null) {
			$this->setToId(NULL);
		} else {
			$this->setToId($v->getId());
		}

		$this->aMemberRelatedByToId = $v;

						if ($v !== null) {
			$v->addIntroEssayRelatedByToId($this);
		}

		return $this;
	}


	
	public function getMemberRelatedByToId(PropelPDO $con = null)
	{
		if ($this->aMemberRelatedByToId === null && ($this->to_id !== null)) {
			$c = new Criteria(MemberPeer::DATABASE_NAME);
			$c->add(MemberPeer::ID, $this->to_id);
			$this->aMemberRelatedByToId = MemberPeer::doSelectOne($c, $con);
			
		}
		return $this->aMemberRelatedByToId;
	}

	
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} 
			$this->aMemberRelatedByFromId = null;
			$this->aMemberRelatedByToId = null;
	}

} 