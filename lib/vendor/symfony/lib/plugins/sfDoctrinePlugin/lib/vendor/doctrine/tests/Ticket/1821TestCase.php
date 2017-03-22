<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Ticket_1821_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 * @author      Andrea Baron <andrea@bhweb.it>
 */
class Doctrine_Ticket_1821_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array(
            'Doctrine_Ticket_1821_Record',
            'Doctrine_Ticket_1821_Record_ID_Aliased',
            'Doctrine_Ticket_1821_Record_Column_Aliased',
            'Doctrine_Ticket_1821_Record_Full_Aliased',
        );
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        
    }
    
    public function execTest($klass)
    {
        //stores old validation setting
        $validation = Doctrine_Manager::getInstance()->getAttribute(Doctrine_Core::ATTR_VALIDATE);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        
        $record = new $klass();
		$record->name = 'test';
		try {
		    $record->save();
		}
		catch(Exception $e) {
		    $this->fail(
		        'Failed to execute validation with class = "' . $klass 
		        . '". Exception message is: ' . $e->getMessage()
		    );
		}
		$this->pass();
		
		Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, $validation);
    }
    
    public function testShouldAllowNotUsingAliases()
    {
        $this->execTest('Doctrine_Ticket_1821_Record');
    }
    
    public function testShouldAllowUsingAliasesOnId()
    {
        $this->execTest('Doctrine_Ticket_1821_Record_ID_Aliased');
    }
    
    public function testShouldAllowUsingAliasesOnColumn()
    {
        $this->execTest('Doctrine_Ticket_1821_Record_Column_Aliased');
    }
    
    public function testShouldAllowUsingAliasesOnBoth()
    {
        $this->execTest('Doctrine_Ticket_1821_Record_Full_Aliased');
    }
}
        
class Doctrine_Ticket_1821_Record_Full_Aliased extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('user_id as id', 'integer', 4, array(
                'autoincrement' => true,
                'notnull' => true,
                'primary' => true
                ));
        $this->hasColumn('user_name as name', 'string', 255, array(
                'notnull' => true,
                'unique' => true
                ));
    }
}

class Doctrine_Ticket_1821_Record_ID_Aliased extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('user_id as id', 'integer', 4, array(
                'autoincrement' => true,
                'notnull' => true,
                'primary' => true
                ));
        $this->hasColumn('name', 'string', 255, array(
                'notnull' => true,
                'unique' => true
                ));
    }
}

class Doctrine_Ticket_1821_Record_Column_Aliased extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array(
                'autoincrement' => true,
                'notnull' => true,
                'primary' => true
                ));
        $this->hasColumn('user_name as name', 'string', 255, array(
                'notnull' => true,
                'unique' => true
                ));
    }
}

class Doctrine_Ticket_1821_Record extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array(
                'autoincrement' => true,
                'notnull' => true,
                'primary' => true
                ));
        $this->hasColumn('name', 'string', 255, array(
                'notnull' => true,
                'unique' => true
                ));
    }
}
