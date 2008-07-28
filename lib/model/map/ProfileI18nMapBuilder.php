<?php



class ProfileI18nMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.ProfileI18nMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('profile_i18n');
		$tMap->setPhpName('ProfileI18n');

		$tMap->setUseIdGenerator(false);

		$tMap->addColumn('NAME', 'Name', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('CAPTION', 'Caption', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addColumn('INFO', 'Info', 'string', CreoleTypes::LONGVARCHAR, false, null);

		$tMap->addForeignPrimaryKey('ID', 'Id', 'int' , CreoleTypes::INTEGER, 'profile', 'ID', true, null);

		$tMap->addPrimaryKey('CULTURE', 'Culture', 'string', CreoleTypes::VARCHAR, true, 7);

	} 
} 