<?php



class CommunityMemberMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.CommunityMemberMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('community_member');
		$tMap->setPhpName('CommunityMember');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('COMMUNITY_ID', 'CommunityId', 'int', CreoleTypes::INTEGER, 'community', 'ID', true, null);

		$tMap->addForeignKey('MEMBER_ID', 'MemberId', 'int', CreoleTypes::INTEGER, 'member', 'ID', true, null);

		$tMap->addColumn('POSITION', 'Position', 'string', CreoleTypes::VARCHAR, false, 32);

	} 
} 