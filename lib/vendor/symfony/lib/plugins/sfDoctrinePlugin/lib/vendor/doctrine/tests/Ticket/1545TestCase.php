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
 * Doctrine_Ticket_1545_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1545_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1545_Foo';
        parent::prepareTables();
    }

    public function testTest()
    {
        try {
            $foo = new Ticket_1545_Foo();
            $foo->a = null;
            $this->assertEqual($foo->b, null);
            $foo->custom = 'test';
            $this->assertEqual($foo->custom, $foo->b);
            $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Ticket_1545_Foo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('a', 'string');
    }

    public function setUp()
    {
        $this->unshiftFilter(new Ticket_1545_FooFilter());
    }
}

class Ticket_1545_FooFilter extends Doctrine_Record_Filter
{
    public function init()
    {
        
    }

    public function filterGet(Doctrine_Record $record, $name)
    {
        if ($name == 'b') {
            return $record->a;
        } else if ($name == 'custom') {
            return $record->a;
        }
    }

    public function filterSet(Doctrine_Record $record, $name, $value)
    {
        if ($name == 'custom') {
            return $record->a = $value . '2';
        }
    }
}