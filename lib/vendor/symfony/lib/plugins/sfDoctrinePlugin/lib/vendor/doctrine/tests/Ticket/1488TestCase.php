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
 * Doctrine_Ticket_1488_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1488_TestCase extends Doctrine_UnitTestCase
{
    public function testTest()
    {
        $q = Doctrine_Query::create()
            ->from('T1488_Class1 c1')
            ->leftJoin('c1.Classes2 c2 WITH c2.value BETWEEN c1.min AND c1.max');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.min AS t__min, t.max AS t__max, t2.id AS t2__id, t2.value AS t2__value FROM t1488__class1 t LEFT JOIN t1488__relation t3 ON (t.id = t3.c1_id) LEFT JOIN t1488__class2 t2 ON t2.id = t3.c2_id AND (t2.value BETWEEN t.min AND t.max)');
    }
}

class T1488_Class1 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('min', 'integer');
        $this->hasColumn('max', 'integer');
    }

    public function setUp()
    {
        $this->hasMany('T1488_Class2 as Classes2', array('local'    => 'c1_id',
                                                            'foreign'  => 'c2_id',
                                                            'refClass' => 'T1488_Relation'));
    }
}

class T1488_Class2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('value', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('T1488_Class1 as Classes1', array('local'    => 'c2_id',
                                                          'foreign'  => 'c1_id',
                                                          'refClass' => 'T1488_Relation'));
    }
}


class T1488_Relation extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('c1_id', 'integer');
        $this->hasColumn('c2_id', 'integer');
    }
}