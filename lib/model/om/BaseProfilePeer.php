<?php


abstract class BaseProfilePeer {

	
	const DATABASE_NAME = 'propel';

	
	const TABLE_NAME = 'profile';

	
	const CLASS_DEFAULT = 'lib.model.Profile';

	
	const NUM_COLUMNS = 13;

	
	const NUM_LAZY_LOAD_COLUMNS = 0;


	
	const ID = 'profile.ID';

	
	const NAME = 'profile.NAME';

	
	const IS_REQUIRED = 'profile.IS_REQUIRED';

	
	const IS_UNIQUE = 'profile.IS_UNIQUE';

	
	const FORM_TYPE = 'profile.FORM_TYPE';

	
	const VALUE_TYPE = 'profile.VALUE_TYPE';

	
	const VALUE_REGEXP = 'profile.VALUE_REGEXP';

	
	const VALUE_MIN = 'profile.VALUE_MIN';

	
	const VALUE_MAX = 'profile.VALUE_MAX';

	
	const IS_DISP_REGIST = 'profile.IS_DISP_REGIST';

	
	const IS_DISP_CONFIG = 'profile.IS_DISP_CONFIG';

	
	const IS_DISP_SEARCH = 'profile.IS_DISP_SEARCH';

	
	const SORT_ORDER = 'profile.SORT_ORDER';

	
	private static $phpNameMap = null;


	
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('Id', 'Name', 'IsRequired', 'IsUnique', 'FormType', 'ValueType', 'ValueRegexp', 'ValueMin', 'ValueMax', 'IsDispRegist', 'IsDispConfig', 'IsDispSearch', 'SortOrder', ),
		BasePeer::TYPE_COLNAME => array (ProfilePeer::ID, ProfilePeer::NAME, ProfilePeer::IS_REQUIRED, ProfilePeer::IS_UNIQUE, ProfilePeer::FORM_TYPE, ProfilePeer::VALUE_TYPE, ProfilePeer::VALUE_REGEXP, ProfilePeer::VALUE_MIN, ProfilePeer::VALUE_MAX, ProfilePeer::IS_DISP_REGIST, ProfilePeer::IS_DISP_CONFIG, ProfilePeer::IS_DISP_SEARCH, ProfilePeer::SORT_ORDER, ),
		BasePeer::TYPE_FIELDNAME => array ('id', 'name', 'is_required', 'is_unique', 'form_type', 'value_type', 'value_regexp', 'value_min', 'value_max', 'is_disp_regist', 'is_disp_config', 'is_disp_search', 'sort_order', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
	);

	
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Name' => 1, 'IsRequired' => 2, 'IsUnique' => 3, 'FormType' => 4, 'ValueType' => 5, 'ValueRegexp' => 6, 'ValueMin' => 7, 'ValueMax' => 8, 'IsDispRegist' => 9, 'IsDispConfig' => 10, 'IsDispSearch' => 11, 'SortOrder' => 12, ),
		BasePeer::TYPE_COLNAME => array (ProfilePeer::ID => 0, ProfilePeer::NAME => 1, ProfilePeer::IS_REQUIRED => 2, ProfilePeer::IS_UNIQUE => 3, ProfilePeer::FORM_TYPE => 4, ProfilePeer::VALUE_TYPE => 5, ProfilePeer::VALUE_REGEXP => 6, ProfilePeer::VALUE_MIN => 7, ProfilePeer::VALUE_MAX => 8, ProfilePeer::IS_DISP_REGIST => 9, ProfilePeer::IS_DISP_CONFIG => 10, ProfilePeer::IS_DISP_SEARCH => 11, ProfilePeer::SORT_ORDER => 12, ),
		BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'name' => 1, 'is_required' => 2, 'is_unique' => 3, 'form_type' => 4, 'value_type' => 5, 'value_regexp' => 6, 'value_min' => 7, 'value_max' => 8, 'is_disp_regist' => 9, 'is_disp_config' => 10, 'is_disp_search' => 11, 'sort_order' => 12, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
	);

	
	public static function getMapBuilder()
	{
		return BasePeer::getMapBuilder('lib.model.map.ProfileMapBuilder');
	}
	
	public static function getPhpNameMap()
	{
		if (self::$phpNameMap === null) {
			$map = ProfilePeer::getTableMap();
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
		return str_replace(ProfilePeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	
	public static function addSelectColumns(Criteria $criteria)
	{

		$criteria->addSelectColumn(ProfilePeer::ID);

		$criteria->addSelectColumn(ProfilePeer::NAME);

		$criteria->addSelectColumn(ProfilePeer::IS_REQUIRED);

		$criteria->addSelectColumn(ProfilePeer::IS_UNIQUE);

		$criteria->addSelectColumn(ProfilePeer::FORM_TYPE);

		$criteria->addSelectColumn(ProfilePeer::VALUE_TYPE);

		$criteria->addSelectColumn(ProfilePeer::VALUE_REGEXP);

		$criteria->addSelectColumn(ProfilePeer::VALUE_MIN);

		$criteria->addSelectColumn(ProfilePeer::VALUE_MAX);

		$criteria->addSelectColumn(ProfilePeer::IS_DISP_REGIST);

		$criteria->addSelectColumn(ProfilePeer::IS_DISP_CONFIG);

		$criteria->addSelectColumn(ProfilePeer::IS_DISP_SEARCH);

		$criteria->addSelectColumn(ProfilePeer::SORT_ORDER);

	}

	const COUNT = 'COUNT(profile.ID)';
	const COUNT_DISTINCT = 'COUNT(DISTINCT profile.ID)';

	
	public static function doCount(Criteria $criteria, $distinct = false, $con = null)
	{
				$criteria = clone $criteria;

				$criteria->clearSelectColumns()->clearOrderByColumns();
		if ($distinct || in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->addSelectColumn(ProfilePeer::COUNT_DISTINCT);
		} else {
			$criteria->addSelectColumn(ProfilePeer::COUNT);
		}

				foreach($criteria->getGroupByColumns() as $column)
		{
			$criteria->addSelectColumn($column);
		}

		$rs = ProfilePeer::doSelectRS($criteria, $con);
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
		$objects = ProfilePeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	
	public static function doSelect(Criteria $criteria, $con = null)
	{
		return ProfilePeer::populateObjects(ProfilePeer::doSelectRS($criteria, $con));
	}
	
	public static function doSelectRS(Criteria $criteria, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if (!$criteria->getSelectColumns()) {
			$criteria = clone $criteria;
			ProfilePeer::addSelectColumns($criteria);
		}

				$criteria->setDbName(self::DATABASE_NAME);

						return BasePeer::doSelect($criteria, $con);
	}
	
	public static function populateObjects(ResultSet $rs)
	{
		$results = array();
	
				$cls = ProfilePeer::getOMClass();
		$cls = Propel::import($cls);
				while($rs->next()) {
		
			$obj = new $cls();
			$obj->hydrate($rs);
			$results[] = $obj;
			
		}
		return $results;
	}

  
  public static function doSelectWithI18n(Criteria $c, $culture = null, $con = null)
  {
    if ($culture === null)
    {
      $culture = sfPropel::getDefaultCulture();
    }

        if ($c->getDbName() == Propel::getDefaultDB())
    {
      $c->setDbName(self::DATABASE_NAME);
    }

    ProfilePeer::addSelectColumns($c);
    $startcol = (ProfilePeer::NUM_COLUMNS - ProfilePeer::NUM_LAZY_LOAD_COLUMNS) + 1;

    ProfileI18nPeer::addSelectColumns($c);

    $c->addJoin(ProfilePeer::ID, ProfileI18nPeer::ID);
    $c->add(ProfileI18nPeer::CULTURE, $culture);

    $rs = BasePeer::doSelect($c, $con);
    $results = array();

    while($rs->next()) {

      $omClass = ProfilePeer::getOMClass();

      $cls = Propel::import($omClass);
      $obj1 = new $cls();
      $obj1->hydrate($rs);
      $obj1->setCulture($culture);

      $omClass = ProfileI18nPeer::getOMClass($rs, $startcol);

      $cls = Propel::import($omClass);
      $obj2 = new $cls();
      $obj2->hydrate($rs, $startcol);

      $obj1->setProfileI18nForCulture($obj2, $culture);
      $obj2->setProfile($obj1);

      $results[] = $obj1;
    }
    return $results;
  }


  
  public static function getI18nModel()
  {
    return 'ProfileI18n';
  }


  static public function getUniqueColumnNames()
  {
    return array();
  }
	
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	
	public static function getOMClass()
	{
		return ProfilePeer::CLASS_DEFAULT;
	}

	
	public static function doInsert($values, $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(self::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} else {
			$criteria = $values->buildCriteria(); 		}

		$criteria->remove(ProfilePeer::ID); 

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
			$comparison = $criteria->getComparison(ProfilePeer::ID);
			$selectCriteria->add(ProfilePeer::ID, $criteria->remove(ProfilePeer::ID), $comparison);

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
			$affectedRows += BasePeer::doDeleteAll(ProfilePeer::TABLE_NAME, $con);
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
			$con = Propel::getConnection(ProfilePeer::DATABASE_NAME);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; 		} elseif ($values instanceof Profile) {

			$criteria = $values->buildPkeyCriteria();
		} else {
						$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(ProfilePeer::ID, (array) $values, Criteria::IN);
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

	
	public static function doValidate(Profile $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ProfilePeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ProfilePeer::TABLE_NAME);

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

		$res =  BasePeer::doValidate(ProfilePeer::DATABASE_NAME, ProfilePeer::TABLE_NAME, $columns);
    if ($res !== true) {
        $request = sfContext::getInstance()->getRequest();
        foreach ($res as $failed) {
            $col = ProfilePeer::translateFieldname($failed->getColumn(), BasePeer::TYPE_COLNAME, BasePeer::TYPE_PHPNAME);
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

		$criteria = new Criteria(ProfilePeer::DATABASE_NAME);

		$criteria->add(ProfilePeer::ID, $pk);


		$v = ProfilePeer::doSelect($criteria, $con);

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
			$criteria->add(ProfilePeer::ID, $pks, Criteria::IN);
			$objs = ProfilePeer::doSelect($criteria, $con);
		}
		return $objs;
	}

} 
if (Propel::isInit()) {
			try {
		BaseProfilePeer::getMapBuilder();
	} catch (Exception $e) {
		Propel::log('Could not initialize Peer: ' . $e->getMessage(), Propel::LOG_ERR);
	}
} else {
			Propel::registerMapBuilder('lib.model.map.ProfileMapBuilder');
}
