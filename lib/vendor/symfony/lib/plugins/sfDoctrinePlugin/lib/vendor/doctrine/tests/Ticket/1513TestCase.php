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
 * Doctrine_Ticket_1513_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1513_TestCase extends Doctrine_UnitTestCase
{
    public function testTest()
    {
        $q = Doctrine_Query::create()
            ->from('T1513_Class2 c2')
            ->leftJoin('c2.Classes1 c1 WITH (c1.max - c1.min) > 50');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.value AS t__value, t2.id AS t2__id, t2.min AS t2__min, t2.max AS t2__max FROM t1513__class2 t LEFT JOIN t1513__relation t3 ON (t.id = t3.c2_id) LEFT JOIN t1513__class1 t2 ON t2.id = t3.c1_id AND ((t2.max - t2.min) > 50)');
    }
}

class T1513_Class1 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('min', 'integer');
        $this->hasColumn('max', 'integer');
    }

    public function setUp()
    {
        $this->hasMany('T1513_Class2 as Classes2', array('local'    => 'c1_id',
                                                            'foreign'  => 'c2_id',
                                                            'refClass' => 'T1513_Relation'));
    }
}

class T1513_Class2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('value', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('T1513_Class1 as Classes1', array('local'    => 'c2_id',
                                                          'foreign'  => 'c1_id',
                                                          'refClass' => 'T1513_Relation'));
    }
}


class T1513_Relation extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('c1_id', 'integer');
        $this->hasColumn('c2_id', 'integer');
    }
}