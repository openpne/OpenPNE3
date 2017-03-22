<?php
class Doctrine_Configurable_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {

    }

    public function prepareData()
    {

    }

    public function testGetIndexNameFormatAttribute()
    {
        // default index name format is %_idx
        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT), '%s_idx');
    }

    public function testGetSequenceNameFormatAttribute()
    {
        // default sequence name format is %_seq
        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_SEQNAME_FORMAT), '%s_seq');
    }

    public function testSetIndexNameFormatAttribute()
    {
        $original = $this->manager->getAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT);
        $this->manager->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, '%_index');

        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT), '%_index');
        $this->manager->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, $original);
    }

    public function testSetSequenceNameFormatAttribute()
    {
        $original = $this->manager->getAttribute(Doctrine_Core::ATTR_SEQNAME_FORMAT);
        $this->manager->setAttribute(Doctrine_Core::ATTR_SEQNAME_FORMAT, '%_sequence');

        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_SEQNAME_FORMAT), '%_sequence');
        $this->manager->setAttribute(Doctrine_Core::ATTR_SEQNAME_FORMAT, $original);
    }

    public function testExceptionIsThrownWhenSettingIndexNameFormatAttributeAtTableLevel()
    {
        try {
            $this->connection->getTable('Entity')->setAttribute(Doctrine_Core::ATTR_IDXNAME_FORMAT, '%s_idx');
            $this->fail();
        } catch(Doctrine_Exception $e) {
            $this->pass();
        }
    }

    public function testExceptionIsThrownWhenSettingSequenceNameFormatAttributeAtTableLevel()
    {
        try {
            $this->connection->getTable('Entity')->setAttribute(Doctrine_Core::ATTR_SEQNAME_FORMAT, '%s_seq');
            $this->fail();
        } catch(Doctrine_Exception $e) {
            $this->pass();
        }
    }

    public function testSettingFieldCaseIsSuccesfulWithZero()
    {
        $original = $this->connection->getAttribute(Doctrine_Core::ATTR_FIELD_CASE);
        try {
            $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, 0);
            $this->pass();
        } catch(Doctrine_Exception $e) {
            $this->fail();
        }
        $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, $original);
    }

    public function testSettingFieldCaseIsSuccesfulWithCaseConstants()
    {
        $original = $this->connection->getAttribute(Doctrine_Core::ATTR_FIELD_CASE);
        try {
            $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, CASE_LOWER);
            $this->pass();
        } catch(Doctrine_Exception $e) {
            $this->fail();
        }
        $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, $original);
    }

    public function testSettingFieldCaseIsSuccesfulWithCaseConstants2()
    {
        $original = $this->connection->getAttribute(Doctrine_Core::ATTR_FIELD_CASE);
        try {
            $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, CASE_UPPER);
            $this->pass();
        } catch(Doctrine_Exception $e) {
            $this->fail();
        }
        $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, $original);
    }

    public function testExceptionIsThrownWhenSettingFieldCaseToNotZeroOneOrTwo()
    {
        try {
            $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, -1);
            $this->fail();
        } catch(Doctrine_Exception $e) {
            $this->pass();
        }
    }

    public function testExceptionIsThrownWhenSettingFieldCaseToNotZeroOneOrTwo2()
    {
        try {
            $this->connection->setAttribute(Doctrine_Core::ATTR_FIELD_CASE, 5);
            $this->fail();
        } catch(Doctrine_Exception $e) {
            $this->pass();
        }
    }

    public function testDefaultQuoteIdentifierAttributeValueIsFalse()
    {
        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER), false);
    }

    public function testQuoteIdentifierAttributeAcceptsBooleans()
    {
        $this->manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER), true);
        $this->manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);
    }

    public function testDefaultSequenceColumnNameAttributeValueIsId()
    {
        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_SEQCOL_NAME), 'id');
    }

    public function testSequenceColumnNameAttributeAcceptsStrings()
    {
        $original = $this->manager->getAttribute(Doctrine_Core::ATTR_SEQCOL_NAME);
        $this->manager->setAttribute(Doctrine_Core::ATTR_SEQCOL_NAME, 'sequence');

        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_SEQCOL_NAME), 'sequence');
        $this->manager->setAttribute(Doctrine_Core::ATTR_SEQCOL_NAME, $original);
    }

    public function testValidatorAttributeAcceptsBooleans()
    {
        $this->manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, true);
        
        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_VALIDATE), true);
        $this->manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, false);
    }

    public function testDefaultPortabilityAttributeValueIsAll()
    {
        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_PORTABILITY), Doctrine_Core::PORTABILITY_NONE);
    }

    public function testPortabilityAttributeAcceptsPortabilityConstants()
    {
        $this->manager->setAttribute(Doctrine_Core::ATTR_PORTABILITY, Doctrine_Core::PORTABILITY_RTRIM | Doctrine_Core::PORTABILITY_FIX_CASE);

        $this->assertEqual($this->manager->getAttribute(Doctrine_Core::ATTR_PORTABILITY), 
                           Doctrine_Core::PORTABILITY_RTRIM | Doctrine_Core::PORTABILITY_FIX_CASE);
        $this->manager->setAttribute(Doctrine_Core::ATTR_PORTABILITY, Doctrine_Core::PORTABILITY_ALL);
    }

    public function testDefaultListenerIsDoctrineEventListener()
    {
        $this->assertTrue($this->manager->getAttribute(Doctrine_Core::ATTR_LISTENER) instanceof Doctrine_EventListener);                                                                     
    }

    public function testListenerAttributeAcceptsEventListenerObjects()
    {
        $original = $this->manager->getAttribute(Doctrine_Core::ATTR_LISTENER);
        $this->manager->setAttribute(Doctrine_Core::ATTR_LISTENER, new Doctrine_EventListener());

        $this->assertTrue($this->manager->getAttribute(Doctrine_Core::ATTR_LISTENER) instanceof Doctrine_EventListener);
        $this->manager->setAttribute(Doctrine_Core::ATTR_LISTENER, $original);
    }

    public function testCollectionKeyAttributeAcceptsValidColumnName()
    {
        $original = $this->connection->getTable('User')->getAttribute(Doctrine_Core::ATTR_COLL_KEY);
        try {
            $this->connection->getTable('User')->setAttribute(Doctrine_Core::ATTR_COLL_KEY, 'name');
            
            $this->pass();
        } catch(Exception $e) {
            $this->fail();
        }
        $this->connection->getTable('User')->setAttribute(Doctrine_Core::ATTR_COLL_KEY, $original);
    }

    public function testSettingInvalidColumnNameToCollectionKeyAttributeThrowsException()
    {
        try {
            $this->connection->getTable('User')->setAttribute(Doctrine_Core::ATTR_COLL_KEY, 'unknown');
            
            $this->fail();
        } catch(Exception $e) {
            $this->pass();
        }
    }

    public function testSettingCollectionKeyAttributeOnOtherThanTableLevelThrowsException()
    {
        try {
            $this->connection->setAttribute(Doctrine_Core::ATTR_COLL_KEY, 'name');
            
            $this->fail();
        } catch(Exception $e) {
            $this->pass();
        }
    }

    public function testGetAttributes()
    {
        $this->assertTrue(is_array($this->manager->getAttributes()));
    }
}