<?php


abstract class BaseMemberProfile extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $member_id;


	
	protected $profile_id;


	
	protected $profile_option_id;


	
	protected $value;

	
	protected $aMember;

	
	protected $aProfile;

	
	protected $aProfileOption;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getMemberId()
	{

		return $this->member_id;
	}

	
	public function getProfileId()
	{

		return $this->profile_id;
	}

	
	public function getProfileOptionId()
	{

		return $this->profile_option_id;
	}

	
	public function getValue()
	{

		return $this->value;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = MemberProfilePeer::ID;
		}

	} 
	
	public function setMemberId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id !== $v) {
			$this->member_id = $v;
			$this->modifiedColumns[] = MemberProfilePeer::MEMBER_ID;
		}

		if ($this->aMember !== null && $this->aMember->getId() !== $v) {
			$this->aMember = null;
		}

	} 
	
	public function setProfileId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->profile_id !== $v) {
			$this->profile_id = $v;
			$this->modifiedColumns[] = MemberProfilePeer::PROFILE_ID;
		}

		if ($this->aProfile !== null && $this->aProfile->getId() !== $v) {
			$this->aProfile = null;
		}

	} 
	
	public function setProfileOptionId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->profile_option_id !== $v) {
			$this->profile_option_id = $v;
			$this->modifiedColumns[] = MemberProfilePeer::PROFILE_OPTION_ID;
		}

		if ($this->aProfileOption !== null && $this->aProfileOption->getId() !== $v) {
			$this->aProfileOption = null;
		}

	} 
	
	public function setValue($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value !== $v) {
			$this->value = $v;
			$this->modifiedColumns[] = MemberProfilePeer::VALUE;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->member_id = $rs->getInt($startcol + 1);

			$this->profile_id = $rs->getInt($startcol + 2);

			$this->profile_option_id = $rs->getInt($startcol + 3);

			$this->value = $rs->getString($startcol + 4);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 5; 
		} catch (Exception $e) {
			throw new PropelException("Error populating MemberProfile object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(MemberProfilePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MemberProfilePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(MemberProfilePeer::DATABASE_NAME);
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


												
			if ($this->aMember !== null) {
				if ($this->aMember->isModified()) {
					$affectedRows += $this->aMember->save($con);
				}
				$this->setMember($this->aMember);
			}

			if ($this->aProfile !== null) {
				if ($this->aProfile->isModified() || ($this->aProfile->getCulture() && $this->aProfile->getCurrentProfileI18n()->isModified())) {
					$affectedRows += $this->aProfile->save($con);
				}
				$this->setProfile($this->aProfile);
			}

			if ($this->aProfileOption !== null) {
				if ($this->aProfileOption->isModified() || ($this->aProfileOption->getCulture() && $this->aProfileOption->getCurrentProfileOptionI18n()->isModified())) {
					$affectedRows += $this->aProfileOption->save($con);
				}
				$this->setProfileOption($this->aProfileOption);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = MemberProfilePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += MemberProfilePeer::doUpdate($this, $con);
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


												
			if ($this->aMember !== null) {
				if (!$this->aMember->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aMember->getValidationFailures());
				}
			}

			if ($this->aProfile !== null) {
				if (!$this->aProfile->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aProfile->getValidationFailures());
				}
			}

			if ($this->aProfileOption !== null) {
				if (!$this->aProfileOption->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aProfileOption->getValidationFailures());
				}
			}


			if (($retval = MemberProfilePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = MemberProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getMemberId();
				break;
			case 2:
				return $this->getProfileId();
				break;
			case 3:
				return $this->getProfileOptionId();
				break;
			case 4:
				return $this->getValue();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = MemberProfilePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getMemberId(),
			$keys[2] => $this->getProfileId(),
			$keys[3] => $this->getProfileOptionId(),
			$keys[4] => $this->getValue(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = MemberProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setMemberId($value);
				break;
			case 2:
				$this->setProfileId($value);
				break;
			case 3:
				$this->setProfileOptionId($value);
				break;
			case 4:
				$this->setValue($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = MemberProfilePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMemberId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setProfileId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setProfileOptionId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setValue($arr[$keys[4]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(MemberProfilePeer::DATABASE_NAME);

		if ($this->isColumnModified(MemberProfilePeer::ID)) $criteria->add(MemberProfilePeer::ID, $this->id);
		if ($this->isColumnModified(MemberProfilePeer::MEMBER_ID)) $criteria->add(MemberProfilePeer::MEMBER_ID, $this->member_id);
		if ($this->isColumnModified(MemberProfilePeer::PROFILE_ID)) $criteria->add(MemberProfilePeer::PROFILE_ID, $this->profile_id);
		if ($this->isColumnModified(MemberProfilePeer::PROFILE_OPTION_ID)) $criteria->add(MemberProfilePeer::PROFILE_OPTION_ID, $this->profile_option_id);
		if ($this->isColumnModified(MemberProfilePeer::VALUE)) $criteria->add(MemberProfilePeer::VALUE, $this->value);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(MemberProfilePeer::DATABASE_NAME);

		$criteria->add(MemberProfilePeer::ID, $this->id);

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

		$copyObj->setMemberId($this->member_id);

		$copyObj->setProfileId($this->profile_id);

		$copyObj->setProfileOptionId($this->profile_option_id);

		$copyObj->setValue($this->value);


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
			self::$peer = new MemberProfilePeer();
		}
		return self::$peer;
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

	
	public function setProfile($v)
	{


		if ($v === null) {
			$this->setProfileId(NULL);
		} else {
			$this->setProfileId($v->getId());
		}


		$this->aProfile = $v;
	}


	
	public function getProfile($con = null)
	{
		if ($this->aProfile === null && ($this->profile_id !== null)) {
						$this->aProfile = ProfilePeer::retrieveByPK($this->profile_id, $con);

			
		}
		return $this->aProfile;
	}

	
	public function setProfileOption($v)
	{


		if ($v === null) {
			$this->setProfileOptionId(NULL);
		} else {
			$this->setProfileOptionId($v->getId());
		}


		$this->aProfileOption = $v;
	}


	
	public function getProfileOption($con = null)
	{
		if ($this->aProfileOption === null && ($this->profile_option_id !== null)) {
						$this->aProfileOption = ProfileOptionPeer::retrieveByPK($this->profile_option_id, $con);

			
		}
		return $this->aProfileOption;
	}

} 