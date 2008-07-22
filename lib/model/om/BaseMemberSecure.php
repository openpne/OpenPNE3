<?php


abstract class BaseMemberSecure extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $member_id = 0;


	
	protected $pc_address;


	
	protected $mobile_address;


	
	protected $regist_address;


	
	protected $password;


	
	protected $password_query_answer;


	
	protected $easy_access_id;


	
	protected $created_at = -32400;


	
	protected $updated_at = -32400;

	
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

	
	public function getPcAddress()
	{

		return $this->pc_address;
	}

	
	public function getMobileAddress()
	{

		return $this->mobile_address;
	}

	
	public function getRegistAddress()
	{

		return $this->regist_address;
	}

	
	public function getPassword()
	{

		return $this->password;
	}

	
	public function getPasswordQueryAnswer()
	{

		return $this->password_query_answer;
	}

	
	public function getEasyAccessId()
	{

		return $this->easy_access_id;
	}

	
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
						$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{

		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
						$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
			}
		} else {
			$ts = $this->updated_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}

	
	public function setId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = MemberSecurePeer::ID;
		}

	} 
	
	public function setMemberId($v)
	{

						if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->member_id !== $v || $v === 0) {
			$this->member_id = $v;
			$this->modifiedColumns[] = MemberSecurePeer::MEMBER_ID;
		}

	} 
	
	public function setPcAddress($v)
	{

								if ($v instanceof Lob && $v === $this->pc_address) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->pc_address !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Clob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->pc_address = $obj;
			$this->modifiedColumns[] = MemberSecurePeer::PC_ADDRESS;
		}

	} 
	
	public function setMobileAddress($v)
	{

								if ($v instanceof Lob && $v === $this->mobile_address) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->mobile_address !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Clob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->mobile_address = $obj;
			$this->modifiedColumns[] = MemberSecurePeer::MOBILE_ADDRESS;
		}

	} 
	
	public function setRegistAddress($v)
	{

								if ($v instanceof Lob && $v === $this->regist_address) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->regist_address !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Clob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->regist_address = $obj;
			$this->modifiedColumns[] = MemberSecurePeer::REGIST_ADDRESS;
		}

	} 
	
	public function setPassword($v)
	{

								if ($v instanceof Lob && $v === $this->password) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->password !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Clob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->password = $obj;
			$this->modifiedColumns[] = MemberSecurePeer::PASSWORD;
		}

	} 
	
	public function setPasswordQueryAnswer($v)
	{

								if ($v instanceof Lob && $v === $this->password_query_answer) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->password_query_answer !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Clob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->password_query_answer = $obj;
			$this->modifiedColumns[] = MemberSecurePeer::PASSWORD_QUERY_ANSWER;
		}

	} 
	
	public function setEasyAccessId($v)
	{

								if ($v instanceof Lob && $v === $this->easy_access_id) {
			$changed = $v->isModified();
		} else {
			$changed = ($this->easy_access_id !== $v);
		}
		if ($changed) {
			if ( !($v instanceof Lob) ) {
				$obj = new Clob();
				$obj->setContents($v);
			} else {
				$obj = $v;
			}
			$this->easy_access_id = $obj;
			$this->modifiedColumns[] = MemberSecurePeer::EASY_ACCESS_ID;
		}

	} 
	
	public function setCreatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts || $ts === -32400) {
			$this->created_at = $ts;
			$this->modifiedColumns[] = MemberSecurePeer::CREATED_AT;
		}

	} 
	
	public function setUpdatedAt($v)
	{

		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) { 				throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts || $ts === -32400) {
			$this->updated_at = $ts;
			$this->modifiedColumns[] = MemberSecurePeer::UPDATED_AT;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->member_id = $rs->getInt($startcol + 1);

			$this->pc_address = $rs->getBlob($startcol + 2);

			$this->mobile_address = $rs->getBlob($startcol + 3);

			$this->regist_address = $rs->getBlob($startcol + 4);

			$this->password = $rs->getBlob($startcol + 5);

			$this->password_query_answer = $rs->getBlob($startcol + 6);

			$this->easy_access_id = $rs->getBlob($startcol + 7);

			$this->created_at = $rs->getTimestamp($startcol + 8, null);

			$this->updated_at = $rs->getTimestamp($startcol + 9, null);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 10; 
		} catch (Exception $e) {
			throw new PropelException("Error populating MemberSecure object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(MemberSecurePeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			MemberSecurePeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
    if ($this->isNew() && !$this->isColumnModified(MemberSecurePeer::CREATED_AT))
    {
      $this->setCreatedAt(time());
    }

    if ($this->isModified() && !$this->isColumnModified(MemberSecurePeer::UPDATED_AT))
    {
      $this->setUpdatedAt(time());
    }

		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(MemberSecurePeer::DATABASE_NAME);
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
					$pk = MemberSecurePeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += MemberSecurePeer::doUpdate($this, $con);
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


			if (($retval = MemberSecurePeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = MemberSecurePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getPcAddress();
				break;
			case 3:
				return $this->getMobileAddress();
				break;
			case 4:
				return $this->getRegistAddress();
				break;
			case 5:
				return $this->getPassword();
				break;
			case 6:
				return $this->getPasswordQueryAnswer();
				break;
			case 7:
				return $this->getEasyAccessId();
				break;
			case 8:
				return $this->getCreatedAt();
				break;
			case 9:
				return $this->getUpdatedAt();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = MemberSecurePeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getMemberId(),
			$keys[2] => $this->getPcAddress(),
			$keys[3] => $this->getMobileAddress(),
			$keys[4] => $this->getRegistAddress(),
			$keys[5] => $this->getPassword(),
			$keys[6] => $this->getPasswordQueryAnswer(),
			$keys[7] => $this->getEasyAccessId(),
			$keys[8] => $this->getCreatedAt(),
			$keys[9] => $this->getUpdatedAt(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = MemberSecurePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setPcAddress($value);
				break;
			case 3:
				$this->setMobileAddress($value);
				break;
			case 4:
				$this->setRegistAddress($value);
				break;
			case 5:
				$this->setPassword($value);
				break;
			case 6:
				$this->setPasswordQueryAnswer($value);
				break;
			case 7:
				$this->setEasyAccessId($value);
				break;
			case 8:
				$this->setCreatedAt($value);
				break;
			case 9:
				$this->setUpdatedAt($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = MemberSecurePeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setMemberId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setPcAddress($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setMobileAddress($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setRegistAddress($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setPassword($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPasswordQueryAnswer($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setEasyAccessId($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setCreatedAt($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setUpdatedAt($arr[$keys[9]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(MemberSecurePeer::DATABASE_NAME);

		if ($this->isColumnModified(MemberSecurePeer::ID)) $criteria->add(MemberSecurePeer::ID, $this->id);
		if ($this->isColumnModified(MemberSecurePeer::MEMBER_ID)) $criteria->add(MemberSecurePeer::MEMBER_ID, $this->member_id);
		if ($this->isColumnModified(MemberSecurePeer::PC_ADDRESS)) $criteria->add(MemberSecurePeer::PC_ADDRESS, $this->pc_address);
		if ($this->isColumnModified(MemberSecurePeer::MOBILE_ADDRESS)) $criteria->add(MemberSecurePeer::MOBILE_ADDRESS, $this->mobile_address);
		if ($this->isColumnModified(MemberSecurePeer::REGIST_ADDRESS)) $criteria->add(MemberSecurePeer::REGIST_ADDRESS, $this->regist_address);
		if ($this->isColumnModified(MemberSecurePeer::PASSWORD)) $criteria->add(MemberSecurePeer::PASSWORD, $this->password);
		if ($this->isColumnModified(MemberSecurePeer::PASSWORD_QUERY_ANSWER)) $criteria->add(MemberSecurePeer::PASSWORD_QUERY_ANSWER, $this->password_query_answer);
		if ($this->isColumnModified(MemberSecurePeer::EASY_ACCESS_ID)) $criteria->add(MemberSecurePeer::EASY_ACCESS_ID, $this->easy_access_id);
		if ($this->isColumnModified(MemberSecurePeer::CREATED_AT)) $criteria->add(MemberSecurePeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(MemberSecurePeer::UPDATED_AT)) $criteria->add(MemberSecurePeer::UPDATED_AT, $this->updated_at);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(MemberSecurePeer::DATABASE_NAME);

		$criteria->add(MemberSecurePeer::ID, $this->id);

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

		$copyObj->setPcAddress($this->pc_address);

		$copyObj->setMobileAddress($this->mobile_address);

		$copyObj->setRegistAddress($this->regist_address);

		$copyObj->setPassword($this->password);

		$copyObj->setPasswordQueryAnswer($this->password_query_answer);

		$copyObj->setEasyAccessId($this->easy_access_id);

		$copyObj->setCreatedAt($this->created_at);

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
			self::$peer = new MemberSecurePeer();
		}
		return self::$peer;
	}

} 