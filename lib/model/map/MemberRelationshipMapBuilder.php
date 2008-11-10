<?php



class MemberRelationshipMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.MemberRelationshipMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('member_relationship');
		$tMap->setPhpName('MemberRelationship');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('MEMBER_ID_TO', 'MemberIdTo', 'int', CreoleTypes::INTEGER, 'member', 'ID', true, null);

		$tMap->addForeignKey('MEMBER_ID_FROM', 'MemberIdFrom', 'int', CreoleTypes::INTEGER, 'member', 'ID', true, null);

		$tMap->addColumn('IS_FRIEND', 'IsFriend', 'boolean', CreoleTypes::BOOLEAN, false, null);

		$tMap->addColumn('IS_FRIEND_PRE', 'IsFriendPre', 'boolean', CreoleTypes::BOOLEAN, false, null);

	} 
} 