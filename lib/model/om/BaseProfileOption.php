<?php


abstract class BaseProfileOption extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $profile_id;


	
	protected $sort_order;

	
	protected $aProfile;

	
	protected $collProfileOptionI18ns;

	
	protected $lastProfileOptionI18nCriteria = null;

	
	protected $collMemberProfiles;

	
	protected $lastMemberProfileCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

  
  protected $culture;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getProfileId()
	{

		return $this->profile_id;
	}

	
	public function getSortOrder()
	{

		return $this->sort_order;
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ProfileOptionPeer::ID;
		}

	} 
	
	public function setProfileId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->profile_id !== $v) {
			$this->profile_id = $v;
			$this->modifiedColumns[] = ProfileOptionPeer::PROFILE_ID;
		}

		if ($this->aProfile !== null && $this->aProfile->getId() !== $v) {
			$this->aProfile = null;
		}

	} 
	
	public function setSortOrder($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->sort_order !== $v) {
			$this->sort_order = $v;
			$this->modifiedColumns[] = ProfileOptionPeer::SORT_ORDER;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->profile_id = $rs->getInt($startcol + 1);

			$this->sort_order = $rs->getInt($startcol + 2);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 3; 
		} catch (Exception $e) {
			throw new PropelException("Error populating ProfileOption object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProfileOptionPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ProfileOptionPeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ProfileOptionPeer::DATABASE_NAME);
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


												
			if ($this->aProfile !== null) {
				if ($this->aProfile->isModified() || ($this->aProfile->getCulture() && $this->aProfile->getCurrentProfileI18n()->isModified())) {
					$affectedRows += $this->aProfile->save($con);
				}
				$this->setProfile($this->aProfile);
			}


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ProfileOptionPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += ProfileOptionPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			if ($this->collProfileOptionI18ns !== null) {
				foreach($this->collProfileOptionI18ns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collMemberProfiles !== null) {
				foreach($this->collMemberProfiles as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

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


												
			if ($this->aProfile !== null) {
				if (!$this->aProfile->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aProfile->getValidationFailures());
				}
			}


			if (($retval = ProfileOptionPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collProfileOptionI18ns !== null) {
					foreach($this->collProfileOptionI18ns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collMemberProfiles !== null) {
					foreach($this->collMemberProfiles as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProfileOptionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getProfileId();
				break;
			case 2:
				return $this->getSortOrder();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProfileOptionPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getProfileId(),
			$keys[2] => $this->getSortOrder(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProfileOptionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setProfileId($value);
				break;
			case 2:
				$this->setSortOrder($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProfileOptionPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setProfileId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setSortOrder($arr[$keys[2]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ProfileOptionPeer::DATABASE_NAME);

		if ($this->isColumnModified(ProfileOptionPeer::ID)) $criteria->add(ProfileOptionPeer::ID, $this->id);
		if ($this->isColumnModified(ProfileOptionPeer::PROFILE_ID)) $criteria->add(ProfileOptionPeer::PROFILE_ID, $this->profile_id);
		if ($this->isColumnModified(ProfileOptionPeer::SORT_ORDER)) $criteria->add(ProfileOptionPeer::SORT_ORDER, $this->sort_order);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ProfileOptionPeer::DATABASE_NAME);

		$criteria->add(ProfileOptionPeer::ID, $this->id);

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

		$copyObj->setProfileId($this->profile_id);

		$copyObj->setSortOrder($this->sort_order);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getProfileOptionI18ns() as $relObj) {
				$copyObj->addProfileOptionI18n($relObj->copy($deepCopy));
			}

			foreach($this->getMemberProfiles() as $relObj) {
				$copyObj->addMemberProfile($relObj->copy($deepCopy));
			}

		} 

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
			self::$peer = new ProfileOptionPeer();
		}
		return self::$peer;
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

	
	public function initProfileOptionI18ns()
	{
		if ($this->collProfileOptionI18ns === null) {
			$this->collProfileOptionI18ns = array();
		}
	}

	
	public function getProfileOptionI18ns($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collProfileOptionI18ns === null) {
			if ($this->isNew()) {
			   $this->collProfileOptionI18ns = array();
			} else {

				$criteria->add(ProfileOptionI18nPeer::ID, $this->getId());

				ProfileOptionI18nPeer::addSelectColumns($criteria);
				$this->collProfileOptionI18ns = ProfileOptionI18nPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(ProfileOptionI18nPeer::ID, $this->getId());

				ProfileOptionI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastProfileOptionI18nCriteria) || !$this->lastProfileOptionI18nCriteria->equals($criteria)) {
					$this->collProfileOptionI18ns = ProfileOptionI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastProfileOptionI18nCriteria = $criteria;
		return $this->collProfileOptionI18ns;
	}

	
	public function countProfileOptionI18ns($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(ProfileOptionI18nPeer::ID, $this->getId());

		return ProfileOptionI18nPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addProfileOptionI18n(ProfileOptionI18n $l)
	{
		$this->collProfileOptionI18ns[] = $l;
		$l->setProfileOption($this);
	}

	
	public function initMemberProfiles()
	{
		if ($this->collMemberProfiles === null) {
			$this->collMemberProfiles = array();
		}
	}

	
	public function getMemberProfiles($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMemberProfiles === null) {
			if ($this->isNew()) {
			   $this->collMemberProfiles = array();
			} else {

				$criteria->add(MemberProfilePeer::PROFILE_OPTION_ID, $this->getId());

				MemberProfilePeer::addSelectColumns($criteria);
				$this->collMemberProfiles = MemberProfilePeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(MemberProfilePeer::PROFILE_OPTION_ID, $this->getId());

				MemberProfilePeer::addSelectColumns($criteria);
				if (!isset($this->lastMemberProfileCriteria) || !$this->lastMemberProfileCriteria->equals($criteria)) {
					$this->collMemberProfiles = MemberProfilePeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastMemberProfileCriteria = $criteria;
		return $this->collMemberProfiles;
	}

	
	public function countMemberProfiles($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(MemberProfilePeer::PROFILE_OPTION_ID, $this->getId());

		return MemberProfilePeer::doCount($criteria, $distinct, $con);
	}

	
	public function addMemberProfile(MemberProfile $l)
	{
		$this->collMemberProfiles[] = $l;
		$l->setProfileOption($this);
	}


	
	public function getMemberProfilesJoinProfile($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collMemberProfiles === null) {
			if ($this->isNew()) {
				$this->collMemberProfiles = array();
			} else {

				$criteria->add(MemberProfilePeer::PROFILE_OPTION_ID, $this->getId());

				$this->collMemberProfiles = MemberProfilePeer::doSelectJoinProfile($criteria, $con);
			}
		} else {
									
			$criteria->add(MemberProfilePeer::PROFILE_OPTION_ID, $this->getId());

			if (!isset($this->lastMemberProfileCriteria) || !$this->lastMemberProfileCriteria->equals($criteria)) {
				$this->collMemberProfiles = MemberProfilePeer::doSelectJoinProfile($criteria, $con);
			}
		}
		$this->lastMemberProfileCriteria = $criteria;

		return $this->collMemberProfiles;
	}

  public function getCulture()
  {
    return $this->culture;
  }

  public function setCulture($culture)
  {
    $this->culture = $culture;
  }

  public function getValue($culture = null)
  {
    return $this->getCurrentProfileOptionI18n($culture)->getValue();
  }

  public function setValue($value, $culture = null)
  {
    $this->getCurrentProfileOptionI18n($culture)->setValue($value);
  }

  protected $current_i18n = array();

  public function getCurrentProfileOptionI18n($culture = null)
  {
    if (is_null($culture))
    {
      $culture = is_null($this->culture) ? sfPropel::getDefaultCulture() : $this->culture;
    }

    if (!isset($this->current_i18n[$culture]))
    {
      $obj = ProfileOptionI18nPeer::retrieveByPK($this->getId(), $culture);
      if ($obj)
      {
        $this->setProfileOptionI18nForCulture($obj, $culture);
      }
      else
      {
        $this->setProfileOptionI18nForCulture(new ProfileOptionI18n(), $culture);
        $this->current_i18n[$culture]->setCulture($culture);
      }
    }

    return $this->current_i18n[$culture];
  }

  public function setProfileOptionI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addProfileOptionI18n($object);
  }

} 