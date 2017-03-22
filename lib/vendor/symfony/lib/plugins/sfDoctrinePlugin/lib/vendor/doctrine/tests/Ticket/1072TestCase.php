<?php

/**
 * Relation testing (accessing) throws an Doctrine_Record_Exception
 * with message 'Unknown record property / related component
 * "payment_detail_id" on "T1072BankTransaction"'.
 * 
 * It happens if I access the relation, save the record, and access
 * the relation column (in this order).
 * 
 * UPDATE:
 * There are three addition checks for the column value type that
 * must be NULL and not an object which is not true after accessing
 * the relation.
 */
class Doctrine_Ticket_1072_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData()
    {
    }

    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'T1072BankTransaction';
        $this->tables[] = 'T1072PaymentDetail';
        parent::prepareTables();
    }

    public function testTicket()
    {
        $bt = new T1072BankTransaction();
        $bt->name = 'Test Bank Transaction';
        
        // (additional check: value must be NULL)
        $this->assertEqual(gettype($bt->payment_detail_id), gettype(null));
        
        // If I access this relation...
        
        if ($bt->T1072PaymentDetail) {
        }
        
        // (additional check: value must still be NULL not an object)
        // [romanb]: This is expected behavior currently. Accessing a related record will create
        // a new one if there is none yet. This makes it possible to use syntax like:
        // $record = new Record();
        // $record->Related->name = 'foo'; // will implicitly create a new Related
        // In addition the foreign key field is set to a reference to the new record (ugh..).
        // No way to change this behavior at the moment for BC reasons.
        $this->assertEqual(gettype($bt->payment_detail_id), gettype(null));
        
        // ...save...
        // [romanb]: Related T1072PaymentDetail will not be saved because its not modified
        // (isModified() == false)
        $bt->save();
        
        try {
            // ...and access the relation column it will throw
            // an exception here but it shouldn't.
            // [romanb]: This has been fixed now. $bt->payment_detail_id will be an empty
            // object as before.
            if ($bt->payment_detail_id) {
            }
            
            // (additional check: value must still be NULL not an object)
            // [romanb]: See above. This is an empty object now, same as before.
            $this->assertEqual(gettype($bt->payment_detail_id), gettype(null));
            
            $this->pass();
        } catch (Doctrine_Record_Exception $e) {
            $this->fail($e->getMessage());
        }
    }
    
    
    public function testTicket2()
    {
        $bt = new T1072BankTransaction();
        $bt->name = 'Test Bank Transaction';

        try {
            // [romanb]: Accessing a related record will create
            // a new one if there is none yet. This makes it possible to use syntax like:
            // $record = new Record();
            // $record->Related->name = 'foo'; // will implicitly create a new Related
            $bt->T1072PaymentDetail->name = 'foo';

            $this->assertEqual(gettype($bt->T1072PaymentDetail), 'object');
            $this->assertEqual(gettype($bt->T1072PaymentDetail->name), 'string');
            $this->assertEqual(gettype($bt->payment_detail_id), gettype(null));
			
            $bt->save();
			
            // After the object gets saved, the foreign key is finally set
            $this->assertEqual($bt->payment_detail_id, 1);
			
            $this->pass();
        } catch (Doctrine_Record_Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class T1072BankTransaction extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('t1072_bank_transaction');
        $this->hasColumn('payment_detail_id', 'integer', null);
        $this->hasColumn('name', 'string', 255, array('notnull' => true));
        $this->option('charset', 'utf8');
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('T1072PaymentDetail', array('local' => 'payment_detail_id',
                                                  'foreign' => 'id'));
    }
}

class T1072PaymentDetail extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('t1072_payment_detail');
        $this->hasColumn('name', 'string', 255, array('notnull' => true));
        $this->option('charset', 'utf8');
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('T1072BankTransaction', array('local' => 'id',
                                                    'foreign' => 'payment_detail_id'));
    }
}