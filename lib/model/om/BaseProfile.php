<?php


abstract class BaseProfile extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $is_required;


	
	protected $is_unique;


	
	protected $form_type;


	
	protected $value_type;


	
	protected $value_regexp;


	
	protected $value_min;


	
	protected $value_max;


	
	protected $is_disp_regist;


	
	protected $is_disp_config;


	
	protected $is_disp_search;


	
	protected $sort_order;

	
	protected $collProfileI18ns;

	
	protected $lastProfileI18nCriteria = null;

	
	protected $collProfileOptions;

	
	protected $lastProfileOptionCriteria = null;

	
	protected $collMemberProfiles;

	
	protected $lastMemberProfileCriteria = null;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

  
  protected $culture;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getIsRequired()
	{

		return $this->is_required;
	}

	
	public function getIsUnique()
	{

		return $this->is_unique;
	}

	
	public function getFormType()
	{

		return $this->form_type;
	}

	
	public function getValueType()
	{

		return $this->value_type;
	}

	
	public function getValueRegexp()
	{

		return $this->value_regexp;
	}

	
	public function getValueMin()
	{

		return $this->value_min;
	}

	
	public function getValueMax()
	{

		return $this->value_max;
	}

	
	public function getIsDispRegist()
	{

		return $this->is_disp_regist;
	}

	
	public function getIsDispConfig()
	{

		return $this->is_disp_config;
	}

	
	public function getIsDispSearch()
	{

		return $this->is_disp_search;
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
			$this->modifiedColumns[] = ProfilePeer::ID;
		}

	} 
	
	public function setIsRequired($v)
	{

		if ($this->is_required !== $v) {
			$this->is_required = $v;
			$this->modifiedColumns[] = ProfilePeer::IS_REQUIRED;
		}

	} 
	
	public function setIsUnique($v)
	{

		if ($this->is_unique !== $v) {
			$this->is_unique = $v;
			$this->modifiedColumns[] = ProfilePeer::IS_UNIQUE;
		}

	} 
	
	public function setFormType($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->form_type !== $v) {
			$this->form_type = $v;
			$this->modifiedColumns[] = ProfilePeer::FORM_TYPE;
		}

	} 
	
	public function setValueType($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value_type !== $v) {
			$this->value_type = $v;
			$this->modifiedColumns[] = ProfilePeer::VALUE_TYPE;
		}

	} 
	
	public function setValueRegexp($v)
	{

						if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value_regexp !== $v) {
			$this->value_regexp = $v;
			$this->modifiedColumns[] = ProfilePeer::VALUE_REGEXP;
		}

	} 
	
	public function setValueMin($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->value_min !== $v) {
			$this->value_min = $v;
			$this->modifiedColumns[] = ProfilePeer::VALUE_MIN;
		}

	} 
	
	public function setValueMax($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->value_max !== $v) {
			$this->value_max = $v;
			$this->modifiedColumns[] = ProfilePeer::VALUE_MAX;
		}

	} 
	
	public function setIsDispRegist($v)
	{

		if ($this->is_disp_regist !== $v) {
			$this->is_disp_regist = $v;
			$this->modifiedColumns[] = ProfilePeer::IS_DISP_REGIST;
		}

	} 
	
	public function setIsDispConfig($v)
	{

		if ($this->is_disp_config !== $v) {
			$this->is_disp_config = $v;
			$this->modifiedColumns[] = ProfilePeer::IS_DISP_CONFIG;
		}

	} 
	
	public function setIsDispSearch($v)
	{

		if ($this->is_disp_search !== $v) {
			$this->is_disp_search = $v;
			$this->modifiedColumns[] = ProfilePeer::IS_DISP_SEARCH;
		}

	} 
	
	public function setSortOrder($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->sort_order !== $v) {
			$this->sort_order = $v;
			$this->modifiedColumns[] = ProfilePeer::SORT_ORDER;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->is_required = $rs->getBoolean($startcol + 1);

			$this->is_unique = $rs->getBoolean($startcol + 2);

			$this->form_type = $rs->getString($startcol + 3);

			$this->value_type = $rs->getString($startcol + 4);

			$this->value_regexp = $rs->getString($startcol + 5);

			$this->value_min = $rs->getInt($startcol + 6);

			$this->value_max = $rs->getInt($startcol + 7);

			$this->is_disp_regist = $rs->getBoolean($startcol + 8);

			$this->is_disp_config = $rs->getBoolean($startcol + 9);

			$this->is_disp_search = $rs->getBoolean($startcol + 10);

			$this->sort_order = $rs->getInt($startcol + 11);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 12; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Profile object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ProfilePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			ProfilePeer::doDelete($this, $con);
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
			$con = Propel::getConnection(ProfilePeer::DATABASE_NAME);
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


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ProfilePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += ProfilePeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			if ($this->collProfileI18ns !== null) {
				foreach($this->collProfileI18ns as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collProfileOptions !== null) {
				foreach($this->collProfileOptions as $referrerFK) {
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


			if (($retval = ProfilePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collProfileI18ns !== null) {
					foreach($this->collProfileI18ns as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collProfileOptions !== null) {
					foreach($this->collProfileOptions as $referrerFK) {
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
		$pos = ProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getIsRequired();
				break;
			case 2:
				return $this->getIsUnique();
				break;
			case 3:
				return $this->getFormType();
				break;
			case 4:
				return $this->getValueType();
				break;
			case 5:
				return $this->getValueRegexp();
				break;
			case 6:
				return $this->getValueMin();
				break;
			case 7:
				return $this->getValueMax();
				break;
			case 8:
				return $this->getIsDispRegist();
				break;
			case 9:
				return $this->getIsDispConfig();
				break;
			case 10:
				return $this->getIsDispSearch();
				break;
			case 11:
				return $this->getSortOrder();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProfilePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getIsRequired(),
			$keys[2] => $this->getIsUnique(),
			$keys[3] => $this->getFormType(),
			$keys[4] => $this->getValueType(),
			$keys[5] => $this->getValueRegexp(),
			$keys[6] => $this->getValueMin(),
			$keys[7] => $this->getValueMax(),
			$keys[8] => $this->getIsDispRegist(),
			$keys[9] => $this->getIsDispConfig(),
			$keys[10] => $this->getIsDispSearch(),
			$keys[11] => $this->getSortOrder(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ProfilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setIsRequired($value);
				break;
			case 2:
				$this->setIsUnique($value);
				break;
			case 3:
				$this->setFormType($value);
				break;
			case 4:
				$this->setValueType($value);
				break;
			case 5:
				$this->setValueRegexp($value);
				break;
			case 6:
				$this->setValueMin($value);
				break;
			case 7:
				$this->setValueMax($value);
				break;
			case 8:
				$this->setIsDispRegist($value);
				break;
			case 9:
				$this->setIsDispConfig($value);
				break;
			case 10:
				$this->setIsDispSearch($value);
				break;
			case 11:
				$this->setSortOrder($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ProfilePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setIsRequired($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setIsUnique($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setFormType($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setValueType($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setValueRegexp($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setValueMin($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setValueMax($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setIsDispRegist($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setIsDispConfig($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setIsDispSearch($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setSortOrder($arr[$keys[11]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(ProfilePeer::DATABASE_NAME);

		if ($this->isColumnModified(ProfilePeer::ID)) $criteria->add(ProfilePeer::ID, $this->id);
		if ($this->isColumnModified(ProfilePeer::IS_REQUIRED)) $criteria->add(ProfilePeer::IS_REQUIRED, $this->is_required);
		if ($this->isColumnModified(ProfilePeer::IS_UNIQUE)) $criteria->add(ProfilePeer::IS_UNIQUE, $this->is_unique);
		if ($this->isColumnModified(ProfilePeer::FORM_TYPE)) $criteria->add(ProfilePeer::FORM_TYPE, $this->form_type);
		if ($this->isColumnModified(ProfilePeer::VALUE_TYPE)) $criteria->add(ProfilePeer::VALUE_TYPE, $this->value_type);
		if ($this->isColumnModified(ProfilePeer::VALUE_REGEXP)) $criteria->add(ProfilePeer::VALUE_REGEXP, $this->value_regexp);
		if ($this->isColumnModified(ProfilePeer::VALUE_MIN)) $criteria->add(ProfilePeer::VALUE_MIN, $this->value_min);
		if ($this->isColumnModified(ProfilePeer::VALUE_MAX)) $criteria->add(ProfilePeer::VALUE_MAX, $this->value_max);
		if ($this->isColumnModified(ProfilePeer::IS_DISP_REGIST)) $criteria->add(ProfilePeer::IS_DISP_REGIST, $this->is_disp_regist);
		if ($this->isColumnModified(ProfilePeer::IS_DISP_CONFIG)) $criteria->add(ProfilePeer::IS_DISP_CONFIG, $this->is_disp_config);
		if ($this->isColumnModified(ProfilePeer::IS_DISP_SEARCH)) $criteria->add(ProfilePeer::IS_DISP_SEARCH, $this->is_disp_search);
		if ($this->isColumnModified(ProfilePeer::SORT_ORDER)) $criteria->add(ProfilePeer::SORT_ORDER, $this->sort_order);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ProfilePeer::DATABASE_NAME);

		$criteria->add(ProfilePeer::ID, $this->id);

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

		$copyObj->setIsRequired($this->is_required);

		$copyObj->setIsUnique($this->is_unique);

		$copyObj->setFormType($this->form_type);

		$copyObj->setValueType($this->value_type);

		$copyObj->setValueRegexp($this->value_regexp);

		$copyObj->setValueMin($this->value_min);

		$copyObj->setValueMax($this->value_max);

		$copyObj->setIsDispRegist($this->is_disp_regist);

		$copyObj->setIsDispConfig($this->is_disp_config);

		$copyObj->setIsDispSearch($this->is_disp_search);

		$copyObj->setSortOrder($this->sort_order);


		if ($deepCopy) {
									$copyObj->setNew(false);

			foreach($this->getProfileI18ns() as $relObj) {
				$copyObj->addProfileI18n($relObj->copy($deepCopy));
			}

			foreach($this->getProfileOptions() as $relObj) {
				$copyObj->addProfileOption($relObj->copy($deepCopy));
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
			self::$peer = new ProfilePeer();
		}
		return self::$peer;
	}

	
	public function initProfileI18ns()
	{
		if ($this->collProfileI18ns === null) {
			$this->collProfileI18ns = array();
		}
	}

	
	public function getProfileI18ns($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collProfileI18ns === null) {
			if ($this->isNew()) {
			   $this->collProfileI18ns = array();
			} else {

				$criteria->add(ProfileI18nPeer::ID, $this->getId());

				ProfileI18nPeer::addSelectColumns($criteria);
				$this->collProfileI18ns = ProfileI18nPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(ProfileI18nPeer::ID, $this->getId());

				ProfileI18nPeer::addSelectColumns($criteria);
				if (!isset($this->lastProfileI18nCriteria) || !$this->lastProfileI18nCriteria->equals($criteria)) {
					$this->collProfileI18ns = ProfileI18nPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastProfileI18nCriteria = $criteria;
		return $this->collProfileI18ns;
	}

	
	public function countProfileI18ns($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(ProfileI18nPeer::ID, $this->getId());

		return ProfileI18nPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addProfileI18n(ProfileI18n $l)
	{
		$this->collProfileI18ns[] = $l;
		$l->setProfile($this);
	}

	
	public function initProfileOptions()
	{
		if ($this->collProfileOptions === null) {
			$this->collProfileOptions = array();
		}
	}

	
	public function getProfileOptions($criteria = null, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collProfileOptions === null) {
			if ($this->isNew()) {
			   $this->collProfileOptions = array();
			} else {

				$criteria->add(ProfileOptionPeer::PROFILE_ID, $this->getId());

				ProfileOptionPeer::addSelectColumns($criteria);
				$this->collProfileOptions = ProfileOptionPeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(ProfileOptionPeer::PROFILE_ID, $this->getId());

				ProfileOptionPeer::addSelectColumns($criteria);
				if (!isset($this->lastProfileOptionCriteria) || !$this->lastProfileOptionCriteria->equals($criteria)) {
					$this->collProfileOptions = ProfileOptionPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastProfileOptionCriteria = $criteria;
		return $this->collProfileOptions;
	}

	
	public function countProfileOptions($criteria = null, $distinct = false, $con = null)
	{
				if ($criteria === null) {
			$criteria = new Criteria();
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		$criteria->add(ProfileOptionPeer::PROFILE_ID, $this->getId());

		return ProfileOptionPeer::doCount($criteria, $distinct, $con);
	}

	
	public function addProfileOption(ProfileOption $l)
	{
		$this->collProfileOptions[] = $l;
		$l->setProfile($this);
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

				$criteria->add(MemberProfilePeer::PROFILE_ID, $this->getId());

				MemberProfilePeer::addSelectColumns($criteria);
				$this->collMemberProfiles = MemberProfilePeer::doSelect($criteria, $con);
			}
		} else {
						if (!$this->isNew()) {
												

				$criteria->add(MemberProfilePeer::PROFILE_ID, $this->getId());

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

		$criteria->add(MemberProfilePeer::PROFILE_ID, $this->getId());

		return MemberProfilePeer::doCount($criteria, $distinct, $con);
	}

	
	public function addMemberProfile(MemberProfile $l)
	{
		$this->collMemberProfiles[] = $l;
		$l->setProfile($this);
	}


	
	public function getMemberProfilesJoinProfileOption($criteria = null, $con = null)
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

				$criteria->add(MemberProfilePeer::PROFILE_ID, $this->getId());

				$this->collMemberProfiles = MemberProfilePeer::doSelectJoinProfileOption($criteria, $con);
			}
		} else {
									
			$criteria->add(MemberProfilePeer::PROFILE_ID, $this->getId());

			if (!isset($this->lastMemberProfileCriteria) || !$this->lastMemberProfileCriteria->equals($criteria)) {
				$this->collMemberProfiles = MemberProfilePeer::doSelectJoinProfileOption($criteria, $con);
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

  public function getName($culture = null)
  {
    return $this->getCurrentProfileI18n($culture)->getName();
  }

  public function setName($value, $culture = null)
  {
    $this->getCurrentProfileI18n($culture)->setName($value);
  }

  public function getCaption($culture = null)
  {
    return $this->getCurrentProfileI18n($culture)->getCaption();
  }

  public function setCaption($value, $culture = null)
  {
    $this->getCurrentProfileI18n($culture)->setCaption($value);
  }

  public function getInfo($culture = null)
  {
    return $this->getCurrentProfileI18n($culture)->getInfo();
  }

  public function setInfo($value, $culture = null)
  {
    $this->getCurrentProfileI18n($culture)->setInfo($value);
  }

  protected $current_i18n = array();

  public function getCurrentProfileI18n($culture = null)
  {
    if (is_null($culture))
    {
      $culture = is_null($this->culture) ? sfPropel::getDefaultCulture() : $this->culture;
    }

    if (!isset($this->current_i18n[$culture]))
    {
      $obj = ProfileI18nPeer::retrieveByPK($this->getId(), $culture);
      if ($obj)
      {
        $this->setProfileI18nForCulture($obj, $culture);
      }
      else
      {
        $this->setProfileI18nForCulture(new ProfileI18n(), $culture);
        $this->current_i18n[$culture]->setCulture($culture);
      }
    }

    return $this->current_i18n[$culture];
  }

  public function setProfileI18nForCulture($object, $culture)
  {
    $this->current_i18n[$culture] = $object;
    $this->addProfileI18n($object);
  }

} 