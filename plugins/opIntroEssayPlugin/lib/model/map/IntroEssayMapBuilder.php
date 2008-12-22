<?php



class IntroEssayMapBuilder implements MapBuilder {

	
	const CLASS_NAME = 'plugins.opIntroEssayPlugin.lib.model.map.IntroEssayMapBuilder';

	
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
		$this->dbMap = Propel::getDatabaseMap(IntroEssayPeer::DATABASE_NAME);

		$tMap = $this->dbMap->addTable(IntroEssayPeer::TABLE_NAME);
		$tMap->setPhpName('IntroEssay');
		$tMap->setClassname('IntroEssay');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'INTEGER', true, null);

		$tMap->addForeignKey('FROM_ID', 'FromId', 'INTEGER', 'member', 'ID', false, null);

		$tMap->addForeignKey('TO_ID', 'ToId', 'INTEGER', 'member', 'ID', false, null);

		$tMap->addColumn('CONTENT', 'Content', 'LONGVARCHAR', false, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null);

	} 
} 