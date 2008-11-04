<?php



class FriendPreMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.FriendPreMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('friend_pre');
		$tMap->setPhpName('FriendPre');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('MEMBER_ID_TO', 'MemberIdTo', 'int', CreoleTypes::INTEGER, 'member', 'ID', true, null);

		$tMap->addForeignKey('MEMBER_ID_FROM', 'MemberIdFrom', 'int', CreoleTypes::INTEGER, 'member', 'ID', true, null);

	} 
} 