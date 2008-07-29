<?php


abstract class BaseCommunityMember extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $community_id;


	
	protected $member_id;


	
	protected $position;

	
	protected $aCommunity;

	
	protected $aMember;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getCommunityId()
	{

		return $this->community_id;
	}

	
	public function getMemberId()
	{

		return $this->member_id;
	}

	
	public function getPosition()
	{

		return $this->position;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = CommunityMemberPeer::ID;
		}

	} 
	
	public function setCommunityId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->community_id !== $v) {
			$this->community_id = $v;
			$this->modifiedColumns[] = CommunityMemberPeer::COMMUNITY_ID;
		}

		if ($this->aCommunity !== null && $this->aCommunity->getId() !== $v) {
			$this->aCommunity = null;
		}

	} 
	
	public function setMemberId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id !== $v) {
			$this->member_id = $v;
			$this->modifiedColumns[] = CommunityMemberPeer::MEMBER_ID;
		}

		if ($this->aMember !== null && $this->aMember->getId() !== $v) {
			$this->aMember = null;
		}

	} 
	
	public function setPosition($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->position !== $v) {
			$this->position = $v;
			$this->modifiedColumns[] = CommunityMemberPeer::POSITION;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->community_id = $rs->getInt($startcol + 1);

			$this->member_id = $rs->getInt($startcol + 2);

			$this->position = $rs->getString($startcol + 3);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 4; 
		} catch (Exception $e) {
			throw new PropelException("Error populating CommunityMember object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(CommunityMemberPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			CommunityMemberPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(CommunityMemberPeer::DATABASE_NAME);
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


												
			if ($this->aCommunity !== null) {
				if ($this->aCommunity->isModified()) {
					$affectedRows += $this->aCommunity->save($con);
				}
				$this->setCommunity($this->aCommunity);
			}

			if ($this->aMember !== null) {
				if ($this->aMember->isModified()) {
					$affectedRows += $this->aMember->save($con);
				}
				$this->setMember($this->aMember);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = CommunityMemberPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += CommunityMemberPeer::doUpdate($this, $con);
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


												
			if ($this->aCommunity !== null) {
				if (!$this->aCommunity->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aCommunity->getValidationFailures());
				}
			}

			if ($this->aMember !== null) {
				if (!$this->aMember->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMember->getValidationFailures());
				}
			}


			if (($retval = CommunityMemberPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CommunityMemberPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getCommunityId();
				break;
			case 2:
				return $this->getMemberId();
				break;
			case 3:
				return $this->getPosition();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CommunityMemberPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getCommunityId(),
			$keys[2] => $this->getMemberId(),
			$keys[3] => $this->getPosition(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = CommunityMemberPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setCommunityId($value);
				break;
			case 2:
				$this->setMemberId($value);
				break;
			case 3:
				$this->setPosition($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = CommunityMemberPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setCommunityId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setMemberId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setPosition($arr[$keys[3]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(CommunityMemberPeer::DATABASE_NAME);

		if ($this->isColumnModified(CommunityMemberPeer::ID)) $criteria->add(CommunityMemberPeer::ID, $this->id);
		if ($this->isColumnModified(CommunityMemberPeer::COMMUNITY_ID)) $criteria->add(CommunityMemberPeer::COMMUNITY_ID, $this->community_id);
		if ($this->isColumnModified(CommunityMemberPeer::MEMBER_ID)) $criteria->add(CommunityMemberPeer::MEMBER_ID, $this->member_id);
		if ($this->isColumnModified(CommunityMemberPeer::POSITION)) $criteria->add(CommunityMemberPeer::POSITION, $this->position);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(CommunityMemberPeer::DATABASE_NAME);

		$criteria->add(CommunityMemberPeer::ID, $this->id);

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

		$copyObj->setCommunityId($this->community_id);

		$copyObj->setMemberId($this->member_id);

		$copyObj->setPosition($this->position);


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
			self::$peer = new CommunityMemberPeer();
		}
		return self::$peer;
	}

	
	public function setCommunity($v)
	{


		if ($v === null) {
			$this->setCommunityId(NULL);
		} else {
			$this->setCommunityId($v->getId());
		}


		$this->aCommunity = $v;
	}


	
	public function getCommunity($con = null)
	{
		if ($this->aCommunity === null && ($this->community_id !== null)) {
						$this->aCommunity = CommunityPeer::retrieveByPK($this->community_id, $con);

			
		}
		return $this->aCommunity;
	}

	
	public function setMember($v)
	{


		if ($v === null) {
			$this->setMemberId(NULL);
		} else {
			$this->setMemberId($v->getId());
		}


		$this->aMember = $v;
	}


	
	public function getMember($con = null)
	{
		if ($this->aMember === null && ($this->member_id !== null)) {
						$this->aMember = MemberPeer::retrieveByPK($this->member_id, $con);

			
		}
		return $this->aMember;
	}

} 