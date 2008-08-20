<?php



class AuthenticationLoginIdMapBuilder {

	
	const CLASS_NAME = 'plugins.opAuthLoginIDPlugin.lib.model.map.AuthenticationLoginIdMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('authentication_login_id');
		$tMap->setPhpName('AuthenticationLoginId');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addForeignKey('MEMBER_ID', 'MemberId', 'int', CreoleTypes::INTEGER, 'member', 'ID', false, null);

		$tMap->addColumn('LOGIN_ID', 'LoginId', 'string', CreoleTypes::VARCHAR, false, 128);

		$tMap->addColumn('PASSWORD', 'Password', 'string', CreoleTypes::VARCHAR, false, 32);

	} 
} 