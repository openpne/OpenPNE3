<?php


abstract class BaseMemberRelationship extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $member_id_to;


	
	protected $member_id_from;


	
	protected $is_friend;


	
	protected $is_friend_pre;

	
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

	
	public function getIsFriend()
	{

		return $this->is_friend;
	}

	
	public function getIsFriendPre()
	{

		return $this->is_friend_pre;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = MemberRelationshipPeer::ID;
		}

	} 
	
	public function setMemberIdTo($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id_to !== $v) {
			$this->member_id_to = $v;
			$this->modifiedColumns[] = MemberRelationshipPeer::MEMBER_ID_TO;
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
			$this->modifiedColumns[] = MemberRelationshipPeer::MEMBER_ID_FROM;
		}

		if ($this->aMemberRelatedByMemberIdFrom !== null && $this->aMemberRelatedByMemberIdFrom->getId() !== $v) {
			$this->aMemberRelatedByMemberIdFrom = null;
		}

	} 
	
	public function setIsFriend($v)
	{

		if ($this->is_friend !== $v) {
			$this->is_friend = $v;
			$this->modifiedColumns[] = MemberRelationshipPeer::IS_FRIEND;
		}

	} 
	
	public function setIsFriendPre($v)
	{

		if ($this->is_friend_pre !== $v) {
			$this->is_friend_pre = $v;
			$this->modifiedColumns[] = MemberRelationshipPeer::IS_FRIEND_PRE;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->member_id_to = $rs->getInt($startcol + 1);

			$this->member_id_from = $rs->getInt($startcol + 2);

			$this->is_friend = $rs->getBoolean($startcol + 3);

			$this->is_friend_pre = $rs->getBoolean($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating MemberRelationship object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(MemberRelationshipPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MemberRelationshipPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(MemberRelationshipPeer::DATABASE_NAME);
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
					$pk = MemberRelationshipPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += MemberRelationshipPeer::doUpdate($this, $con);
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


			if (($retval = MemberRelationshipPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = MemberRelationshipPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
			case 3:
				return $this->getIsFriend();
				break;
			case 4:
				return $this->getIsFriendPre();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = MemberRelationshipPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getMemberIdTo(),
			$keys[2] => $this->getMemberIdFrom(),
			$keys[3] => $this->getIsFriend(),
			$keys[4] => $this->getIsFriendPre(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = MemberRelationshipPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
			case 3:
				$this->setIsFriend($value);
				break;
			case 4:
				$this->setIsFriendPre($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = MemberRelationshipPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMemberIdTo($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMemberIdFrom($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setIsFriend($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setIsFriendPre($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(MemberRelationshipPeer::DATABASE_NAME);

		if ($this->isColumnModified(MemberRelationshipPeer::ID)) $criteria->add(MemberRelationshipPeer::ID, $this->id);
		if ($this->isColumnModified(MemberRelationshipPeer::MEMBER_ID_TO)) $criteria->add(MemberRelationshipPeer::MEMBER_ID_TO, $this->member_id_to);
		if ($this->isColumnModified(MemberRelationshipPeer::MEMBER_ID_FROM)) $criteria->add(MemberRelationshipPeer::MEMBER_ID_FROM, $this->member_id_from);
		if ($this->isColumnModified(MemberRelationshipPeer::IS_FRIEND)) $criteria->add(MemberRelationshipPeer::IS_FRIEND, $this->is_friend);
		if ($this->isColumnModified(MemberRelationshipPeer::IS_FRIEND_PRE)) $criteria->add(MemberRelationshipPeer::IS_FRIEND_PRE, $this->is_friend_pre);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(MemberRelationshipPeer::DATABASE_NAME);

		$criteria->add(MemberRelationshipPeer::ID, $this->id);

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

		$copyObj->setIsFriend($this->is_friend);

		$copyObj->setIsFriendPre($this->is_friend_pre);


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
			self::$peer = new MemberRelationshipPeer();
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