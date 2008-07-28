<?php


abstract class BaseFriend extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $member_id_to;


	
	protected $member_id_from;

	
	protected $aMemberRelatedByMemberIdTo;

	
	protected $aMemberRelatedByMemberIdFrom;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getMemberIdTo()
	{

		return $this->member_id_to;
	}

	
	public function getMemberIdFrom()
	{

		return $this->member_id_from;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = FriendPeer::ID;
		}

	} 
	
	public function setMemberIdTo($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id_to !== $v) {
			$this->member_id_to = $v;
			$this->modifiedColumns[] = FriendPeer::MEMBER_ID_TO;
		}

		if ($this->aMemberRelatedByMemberIdTo !== null && $this->aMemberRelatedByMemberIdTo->getId() !== $v) {
			$this->aMemberRelatedByMemberIdTo = null;
		}

	} 
	
	public function setMemberIdFrom($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id_from !== $v) {
			$this->member_id_from = $v;
			$this->modifiedColumns[] = FriendPeer::MEMBER_ID_FROM;
		}

		if ($this->aMemberRelatedByMemberIdFrom !== null && $this->aMemberRelatedByMemberIdFrom->getId() !== $v) {
			$this->aMemberRelatedByMemberIdFrom = null;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->member_id_to = $rs->getInt($startcol + 1);

			$this->member_id_from = $rs->getInt($startcol + 2);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 3; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Friend object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FriendPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			FriendPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(FriendPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


												
			if ($this->aMemberRelatedByMemberIdTo !== null) {
				if ($this->aMemberRelatedByMemberIdTo->isModified()) {
					$affectedRows += $this->aMemberRelatedByMemberIdTo->save($con);
				}
				$this->setMemberRelatedByMemberIdTo($this->aMemberRelatedByMemberIdTo);
			}

			if ($this->aMemberRelatedByMemberIdFrom !== null) {
				if ($this->aMemberRelatedByMemberIdFrom->isModified()) {
					$affectedRows += $this->aMemberRelatedByMemberIdFrom->save($con);
				}
				$this->setMemberRelatedByMemberIdFrom($this->aMemberRelatedByMemberIdFrom);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = FriendPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += FriendPeer::doUpdate($this, $con);
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


												
			if ($this->aMemberRelatedByMemberIdTo !== null) {
				if (!$this->aMemberRelatedByMemberIdTo->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMemberRelatedByMemberIdTo->getValidationFailures());
				}
			}

			if ($this->aMemberRelatedByMemberIdFrom !== null) {
				if (!$this->aMemberRelatedByMemberIdFrom->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMemberRelatedByMemberIdFrom->getValidationFailures());
				}
			}


			if (($retval = FriendPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FriendPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getMemberIdTo();
				break;
			case 2:
				return $this->getMemberIdFrom();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = FriendPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getMemberIdTo(),
			$keys[2] => $this->getMemberIdFrom(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = FriendPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setMemberIdTo($value);
				break;
			case 2:
				$this->setMemberIdFrom($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = FriendPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMemberIdTo($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMemberIdFrom($arr[$keys[2]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(FriendPeer::DATABASE_NAME);

		if ($this->isColumnModified(FriendPeer::ID)) $criteria->add(FriendPeer::ID, $this->id);
		if ($this->isColumnModified(FriendPeer::MEMBER_ID_TO)) $criteria->add(FriendPeer::MEMBER_ID_TO, $this->member_id_to);
		if ($this->isColumnModified(FriendPeer::MEMBER_ID_FROM)) $criteria->add(FriendPeer::MEMBER_ID_FROM, $this->member_id_from);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(FriendPeer::DATABASE_NAME);

		$criteria->add(FriendPeer::ID, $this->id);

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

		$copyObj->setMemberIdTo($this->member_id_to);

		$copyObj->setMemberIdFrom($this->member_id_from);


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
			self::$peer = new FriendPeer();
		}
		return self::$peer;
	}

	
	public function setMemberRelatedByMemberIdTo($v)
	{


		if ($v === null) {
			$this->setMemberIdTo(NULL);
		} else {
			$this->setMemberIdTo($v->getId());
		}


		$this->aMemberRelatedByMemberIdTo = $v;
	}


	
	public function getMemberRelatedByMemberIdTo($con = null)
	{
		if ($this->aMemberRelatedByMemberIdTo === null && ($this->member_id_to !== null)) {
						$this->aMemberRelatedByMemberIdTo = MemberPeer::retrieveByPK($this->member_id_to, $con);

			
		}
		return $this->aMemberRelatedByMemberIdTo;
	}

	
	public function setMemberRelatedByMemberIdFrom($v)
	{


		if ($v === null) {
			$this->setMemberIdFrom(NULL);
		} else {
			$this->setMemberIdFrom($v->getId());
		}


		$this->aMemberRelatedByMemberIdFrom = $v;
	}


	
	public function getMemberRelatedByMemberIdFrom($con = null)
	{
		if ($this->aMemberRelatedByMemberIdFrom === null && ($this->member_id_from !== null)) {
						$this->aMemberRelatedByMemberIdFrom = MemberPeer::retrieveByPK($this->member_id_from, $con);

			
		}
		return $this->aMemberRelatedByMemberIdFrom;
	}

} 