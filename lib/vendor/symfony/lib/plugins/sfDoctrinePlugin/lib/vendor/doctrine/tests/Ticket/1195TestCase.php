<?php
/*
 * Created on Jul 01, 2008
 *
 * by Stefan Klug ( stefan.klug (at) gmail.com )
 */

class Doctrine_Ticket_1195_TestCase extends Doctrine_UnitTestCase
{
	public function prepareTables()
    {
    	$this->tables = array();
    	$this->tables[] = 'T1195_Item';
    	$this->tables[] = 'T1195_Ref';

    	parent :: prepareTables();
    }

    public function prepareData()
    {
        $item = new T1195_Item();
        $item->col1 = "a";
        $item->col2 = "a";
        $item->save();

        $item = new T1195_Item();
        $item->col1 = "a";
        $item->col2 = "b";
        $item->save();

        $item = new T1195_Item();
        $item->col1 = "b";
        $item->col2 = "a";
        $item->save();

        $item = new T1195_Item();
        $item->col1 = "b";
        $item->col2 = "b";
        $item->save();

        $ref = new T1195_Ref();
        $ref->Item = $item;
        $ref->save();

        $ref = new T1195_Ref();
        $ref->Item = $item;
        $ref->save();

    }

    public function testRawSQLaddWhere()
    {
    	//this checks for an error in parseDqlQueryPart

    	$query = new Doctrine_RawSql();
        $q = $query->select('{i.*}')
				->addComponent('i', 'T1195_Item i')
				->from('items i')
				->addWhere('i.col1 = ?','a')
				->addWhere('i.col2 = ?','a');

		$res = $q->execute();

        $this->assertEqual($res->count(), 1);
    }

    public function testRawSQLDistinct()
    {
    	$q = new Doctrine_RawSql();
        $q = $q->select('{i.*}')
				->addComponent('i', 'T1195_Item i')
				->from('ref r')
				->leftJoin('items i ON r.item_id=i.id');


		$res = $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
		$this->assertEqual(sizeof($res), 2);

		$q->distinct();
		$res = $q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
		$this->assertEqual(sizeof($res), 1);
    }

	public function testRawSQLCount()
    {
    	$q = new Doctrine_RawSql();
        $q = $q->select('{i.*}')
				->addComponent('i', 'T1195_Item i')
				->from('items i');

		if ( !method_exists( $q, 'count' ))
		{
			$this->fail("The query doesn't have a count() method");
			return;
		}

		$res = $q->count();
		$this->assertEqual($res, 4);

    }
}

class T1195_Item extends Doctrine_Record
{
    public function setTableDefinition()
    {
       	$this->setTableName('items');
        $this->hasColumn('id', 'integer', null, array('autoincrement' => true, 'primary' => true, 'notnull' => true));
        $this->hasColumn('col1', 'string', 10);
        $this->hasColumn('col2', 'string', 10);
    }
}

class T1195_Ref extends Doctrine_Record
{
    public function setTableDefinition()
    {
       	$this->setTableName('ref');
        $this->hasColumn('id', 'integer', null, array('autoincrement' => true, 'primary' => true, 'notnull' => true));
        $this->hasColumn('item_id', 'integer', null);
    }

    public function setUp()
    {
    	$this->hasOne('T1195_Item as Item', array('local' => 'item_id', 'foreign' => 'id'));
    }
}
