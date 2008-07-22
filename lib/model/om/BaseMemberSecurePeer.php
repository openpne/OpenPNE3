<?php


abstract class BaseMemberSecurePeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'member_secure';

	
	const CLASS_DEFAULT = 'lib.model.MemberSecure';

	
	const NUM_COLUMNS = 10;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'member_secure.ID';

	
	const MEMBER_ID = 'member_secure.MEMBER_ID';

	
	const PC_ADDRESS = 'member_secure.PC_ADDRESS';

	
	const MOBILE_ADDRESS = 'member_secure.MOBILE_ADDRESS';

	
	const REGIST_ADDRESS = 'member_secure.REGIST_ADDRESS';

	
	const PASSWORD = 'member_secure.PASSWORD';

	
	const PASSWORD_QUERY_ANSWER = 'member_secure.PASSWORD_QUERY_ANSWER';

	
	const EASY_ACCESS_ID = 'member_secure.EASY_ACCESS_ID';

	
	const CREATED_AT = 'member_secure.CREATED_AT';

	
	const UPDATED_AT = 'member_secure.UPDATED_AT';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'MemberId', 'PcAddress', 'MobileAddress', 'RegistAddress', 'Password', 'PasswordQueryAnswer', 'EasyAccessId', 'CreatedAt', 'UpdatedAt', ),
		BasePeer::TYPE_COLNAME => array (MemberSecurePeer::ID, MemberSecurePeer::MEMBER_ID, MemberSecurePeer::PC_ADDRESS, MemberSecurePeer::MOBILE_ADDRESS, MemberSecurePeer::REGIST_ADDRESS, MemberSecurePeer::PASSWORD, MemberSecurePeer::PASSWORD_QUERY_ANSWER, MemberSecurePeer::EASY_ACCESS_ID, MemberSecurePeer::CREATED_AT, MemberSecurePeer::UPDATED_AT, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'member_id', 'pc_address', 'mobile_address', 'regist_address', 'password', 'password_query_answer', 'easy_access_id', 'created_at', 'updated_at', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'MemberId' => 1, 'PcAddress' => 2, 'MobileAddress' => 3, 'RegistAddress' => 4, 'Password' => 5, 'PasswordQueryAnswer' => 6, 'EasyAccessId' => 7, 'CreatedAt' => 8, 'UpdatedAt' => 9, ),
		BasePeer::TYPE_COLNAME => array (MemberSecurePeer::ID => 0, MemberSecurePeer::MEMBER_ID => 1, MemberSecurePeer::PC_ADDRESS => 2, MemberSecurePeer::MOBILE_ADDRESS => 3, MemberSecurePeer::REGIST_ADDRESS => 4, MemberSecurePeer::PASSWORD => 5, MemberSecurePeer::PASSWORD_QUERY_ANSWER => 6, MemberSecurePeer::EASY_ACCESS_ID => 7, MemberSecurePeer::CREATED_AT => 8, MemberSecurePeer::UPDATED_AT => 9, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'member_id' => 1, 'pc_address' => 2, 'mobile_address' => 3, 'regist_address' => 4, 'password' => 5, 'password_query_answer' => 6, 'easy_access_id' => 7, 'created_at' => 8, 'updated_at' => 9, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('lib.model.map.MemberSecureMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = MemberSecurePeer::getTableMap();
			$columns = $map->getColumns();
			$nameMap = array();
			foreach ($columns as $column) {
				$nameMap[$column->getPhpName()] = $column->getColumnName();
			}
			self::$phpNameMap = $nameMap;
		}
		return self::$phpNameMap;
	}
	
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants TYPE_PHPNAME, TYPE_COLNAME, TYPE_FIELDNAME, TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	
	public static function alias($alias, $column)
	{
		return str_replace(MemberSecurePeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(MemberSecurePeer::ID);

		$criteria->addSelectColumn(MemberSecurePeer::MEMBER_ID);

		$criteria->addSelectColumn(MemberSecurePeer::PC_ADDRESS);

		$criteria->addSelectColumn(MemberSecurePeer::MOBILE_ADDRESS);

		$criteria->addSelectColumn(MemberSecurePeer::REGIST_ADDRESS);

		$criteria->addSelectColumn(MemberSecurePeer::PASSWORD);

		$criteria->addSelectColumn(MemberSecurePeer::PASSWORD_QUERY_ANSWER);

		$criteria->addSelectColumn(MemberSecurePeer::EASY_ACCESS_ID);

		$criteria->addSelectColumn(MemberSecurePeer::CREATED_AT);

		$criteria->addSelectColumn(MemberSecurePeer::UPDATED_AT);

	}

	const COUNT = 'COUNT(member_secure.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT member_secure.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(MemberSecurePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(MemberSecurePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = MemberSecurePeer::doSelectRS($criteria, $con);
		if ($rs->next()) {
			return $rs->getInt(1);
		} else {
						return 0;
		}
	}
	
	public static function doSelectOne(Criteria $criteria, $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = MemberSecurePeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return MemberSecurePeer::populateObjects(MemberSecurePeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			MemberSecurePeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = MemberSecurePeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

  static public function getUniqueColumnNames()
  {
    return array(array('member_id'));
  }
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return MemberSecurePeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(MemberSecurePeer::ID); 

				$criteria->setDbName(self::DATABASE_NAME);

		try {
									$con->begin();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollback();
			throw $e;
		}

		return $pk;
	}

	
	public static function doUpdate($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; 
			$comparison = $criteria->getComparison(MemberSecurePeer::ID);
			$selectCriteria->add(MemberSecurePeer::ID, $criteria->remove(MemberSecurePeer::ID), $comparison);

		} else { 			$criteria = $values->buildCriteria(); 			$selectCriteria = $values->buildPkeyCriteria(); 		}

				$criteria->setDbName(self::DATABASE_NAME);

		return BasePeer::doUpdate($selectCriteria, $criteria, $con);
	}

	
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}
		$affectedRows = 0; 		try {
									$con->begin();
			$affectedRows += BasePeer::doDeleteAll(MemberSecurePeer::TABLE_NAME, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	 public static function doDelete($values, $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(MemberSecurePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof MemberSecure) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(MemberSecurePeer::ID, (array) $values, Criteria::IN);
		}

				$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; 
		try {
									$con->begin();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public static function doValidate(MemberSecure $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(MemberSecurePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(MemberSecurePeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		$res =  BasePeer::doValidate(MemberSecurePeer::DATABASE_NAME, MemberSecurePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = MemberSecurePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
            $request->setError($col, $failed->getMessage());
        }
    }

    return $res;
	}

	
	public static function retrieveByPK($pk, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$criteria = new Criteria(MemberSecurePeer::DATABASE_NAME);

		$criteria->add(MemberSecurePeer::ID, $pk);


		$v = MemberSecurePeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	
	public static function retrieveByPKs($pks, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria();
			$criteria->add(MemberSecurePeer::ID, $pks, Criteria::IN);
			$objs = MemberSecurePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseMemberSecurePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('lib.model.map.MemberSecureMapBuilder');
}
