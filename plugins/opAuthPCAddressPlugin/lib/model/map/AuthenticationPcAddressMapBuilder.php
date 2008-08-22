<?php



class AuthenticationPcAddressMapBuilder {

	
	const CLASS_NAME = 'plugins.opAuthPCAddressPlugin.lib.model.map.AuthenticationPcAddressMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('authentication_pc_address');
		$tMap->setPhpName('AuthenticationPcAddress');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('MEMBER_ID', 'MemberId', 'int', CreoleTypes::INTEGER, 'member', 'ID', false, null);

		$tMap->addColumn('PASSWORD', 'Password', 'string', CreoleTypes::VARCHAR, false, 32);

		$tMap->addColumn('REGISTER_SESSION', 'RegisterSession', 'string', CreoleTypes::VARCHAR, false, 32);

	} 
} 