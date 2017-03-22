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
 * Doctrine_Query_Registry
 *
 * @package     Doctrine
 * @subpackage  Query
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Table_NamedQuery_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array('MyFoo');
        
        parent::prepareTables();
    }

    public function prepareData() {
        $f1 = new MyFoo();
        $f1->name = 'jwage';
        $f1->value0 = 0;
        $f1->save();

        $f2 = new MyFoo();
        $f2->name = 'jonwage';
        $f2->value0 = 1;
        $f2->save();

        $f3 = new MyFoo();
        $f3->name = 'jonathanwage';
        $f3->value0 = 2;
        $f3->save();
    }
    
    
    public function testNamedQuerySupport()
    {
        $table = Doctrine_Core::getTable('MyFoo');

        $this->assertEqual(
            $table->createNamedQuery('get.by.id')->getSqlQuery(),
            'SELECT m.id AS m__id, m.name AS m__name, m.value0 AS m__value0 FROM my_foo m WHERE (m.id = ?)'
        );
        
        $this->assertEqual(
            $table->createNamedQuery('get.by.similar.names')->getSqlQuery(),
            'SELECT m.id AS m__id, m.value0 AS m__value0 FROM my_foo m WHERE (LOWER(m.name) LIKE LOWER(?))'
        );
        
        $this->assertEqual($table->createNamedQuery('get.by.similar.names')->count(array('%jon%wage%')), 2);
        
        $items = $table->find('get.by.similar.names', array('%jon%wage%'));
        
        $this->assertEqual(count($items), 2);
    }
}


class MyFoo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('value0', 'integer', 4);
    }
}


class MyFooTable extends Doctrine_Table
{
    public function construct()
    {
        $this->addNamedQuery('get.by.id', 'SELECT f.* FROM MyFoo f WHERE f.id = ?');
        $this->addNamedQuery(
            'get.by.similar.names', Doctrine_Query::create()
                ->select('f.id, f.value0')
                ->from('MyFoo f')
                ->where('LOWER(f.name) LIKE LOWER(?)')
        );
    }
}