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
 * Doctrine_TableRemoveColumn_TestCase
 *
 * @package     Doctrine
 * @author      Dennis Verspuij <dennis.verspuij@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Table_RemoveColumn_TestCase extends Doctrine_UnitTestCase
{

    public function prepareTables()
    {
        $this->tables[] = 'RemoveColumnTest';
        parent::prepareTables();
    }

    protected function _verifyNrColumnsAndFields($table, $nrExpected)
    {
        $nrColumns = $table->getColumnCount();

        $this->assertEqual($nrColumns, $nrExpected);
        $this->assertEqual($nrColumns, count($table->getColumns()));

        $this->assertEqual($nrColumns, count($table->getFieldNames()));
        foreach($table->getFieldNames() as $field) {
            $this->assertTrue($table->hasField($field));
        }

        // the following are trivial because both getColumnNames and
        // hasColumn use Table::_columns instead of Table::_fieldNames
        $this->assertEqual($nrColumns, count($table->getColumnNames()));
        foreach($table->getColumnNames() as $column) {
            $this->assertTrue($table->hasColumn($column));
        }
    }

    public function testAfterDefinition()
    {
        $table = $this->connection->getTable('RemoveColumnTest');
        $this->_verifyNrColumnsAndFields($table, 4);
    }

    public function testAfterRemoveColumn()
    {
        $table = $this->connection->getTable('RemoveColumnTest');
        $table->removeColumn('bb');
        $table->removeColumn('CC');
        $this->_verifyNrColumnsAndFields($table, 2);
    }
}

class RemoveColumnTest extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('AA', 'integer', null, array('primary' => true));
        $this->hasColumn('bb', 'integer');
        $this->hasColumn('CC', 'string',10);
        $this->hasColumn('dd', 'string',10);
    }
}