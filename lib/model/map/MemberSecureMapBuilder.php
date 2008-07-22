<?php



class MemberSecureMapBuilder {

	
	const CLASS_NAME = 'lib.model.map.MemberSecureMapBuilder';

	
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

		$tMap = $this->dbMap->addTable('member_secure');
		$tMap->setPhpName('MemberSecure');

		$tMap->setUseIdGenerator(true);

		$tMap->addPrimaryKey('ID', 'Id', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('MEMBER_ID', 'MemberId', 'int', CreoleTypes::INTEGER, true, null);

		$tMap->addColumn('PC_ADDRESS', 'PcAddress', 'string', CreoleTypes::VARBINARY, true, null);

		$tMap->addColumn('MOBILE_ADDRESS', 'MobileAddress', 'string', CreoleTypes::VARBINARY, true, null);

		$tMap->addColumn('REGIST_ADDRESS', 'RegistAddress', 'string', CreoleTypes::VARBINARY, true, null);

		$tMap->addColumn('PASSWORD', 'Password', 'string', CreoleTypes::VARBINARY, true, null);

		$tMap->addColumn('PASSWORD_QUERY_ANSWER', 'PasswordQueryAnswer', 'string', CreoleTypes::VARBINARY, true, null);

		$tMap->addColumn('EASY_ACCESS_ID', 'EasyAccessId', 'string', CreoleTypes::VARBINARY, true, null);

		$tMap->addColumn('CREATED_AT', 'CreatedAt', 'int', CreoleTypes::TIMESTAMP, true, null);

		$tMap->addColumn('UPDATED_AT', 'UpdatedAt', 'int', CreoleTypes::TIMESTAMP, true, null);

	} 
} 