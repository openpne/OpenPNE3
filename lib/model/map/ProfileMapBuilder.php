<?php



class ProfileMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.ProfileMapBuilder';

	
	private $dbMap;

	
	public function isBuilt()
	{
		return ($this->dbMap !== null);
	}

	
	public function getDatabaseMap()
	{
		return $this->dbMap;
	}

	
	public function doBuild()
	{
		$this->dbMap = Propel::getDatabaseMap('propel');

		$tMap = $this->dbMap->addTable('profile');
		$tMap->setPhpName('Profile');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::VARCHAR, true, 64);

		$tMap->addColumn('IS_REQUIRED', 'IsRequired', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('IS_UNIQUE', 'IsUnique', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('FORM_TYPE', 'FormType', 'string', CreoleTypes::VARCHAR, true, 32);

		$tMap->addColumn('VALUE_TYPE', 'ValueType', 'string', CreoleTypes::VARCHAR, true, 32);

		$tMap->addColumn('VALUE_REGEXP', 'ValueRegexp', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('VALUE_MIN', 'ValueMin', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('VALUE_MAX', 'ValueMax', 'int', CreoleTypes::INTEGER, false, null);

		$tMap->addColumn('IS_DISP_REGIST', 'IsDispRegist', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('IS_DISP_CONFIG', 'IsDispConfig', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('IS_DISP_SEARCH', 'IsDispSearch', 'boolean', CreoleTypes::BOOLEAN, true, null);

		$tMap->addColumn('SORT_ORDER', 'SortOrder', 'int', CreoleTypes::INTEGER, false, null);

	} 
} 