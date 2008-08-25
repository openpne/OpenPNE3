<?php



class NaviMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.NaviMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('navi');
		$tMap->setPhpName('Navi');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('TYPE', 'Type', 'string', CreoleTypes::VARCHAR, true, 64);

		$tMap->addColumn('URI', 'Uri', 'string', CreoleTypes::LONGVARCHAR, false, null);

	} 
} 