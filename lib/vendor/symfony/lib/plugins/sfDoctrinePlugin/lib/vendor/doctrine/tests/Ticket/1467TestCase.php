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
 * Doctrine_Ticket_1467_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1467_TestCase extends Doctrine_UnitTestCase 
{
    public function testTicket()
    {
        // SELECT picture_id FROM ItemPicture INNER JOIN Puzzle ON ItemPicture.item_id=ItemPuzzle_item_id
        $q = Doctrine_Query::create()
            ->select('pic.id')
            ->from('T1467_Picture pic')
            ->innerJoin('pic.Items ite')
            ->innerJoin('ite.Puzzles puz');

        $this->assertEqual($q->getDql(), 'SELECT pic.id FROM T1467_Picture pic INNER JOIN pic.Items ite INNER JOIN ite.Puzzles puz');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id FROM t1467__picture t INNER JOIN t1467__item_picture t3 ON (t.id = t3.picture_id) INNER JOIN t1467__item t2 ON t2.id = t3.item_id INNER JOIN t1467__item_puzzle t5 ON (t2.id = t5.item_id) INNER JOIN t1467__puzzle t4 ON t4.id = t5.puzzle_id');
    }
}


class T1467_Item extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 50);
    }

    public function setUp()
    {
        $this->hasMany('T1467_Picture as Pictures', array(
            'refClass' => 'T1467_ItemPicture',
            'local' => 'item_id',
            'foreign' => 'picture_id'
        ));

        $this->hasMany('T1467_Puzzle as Puzzles', array(
            'refClass' => 'T1467_ItemPuzzle',
            'local' => 'item_id',
            'foreign' => 'puzzle_id'
        ));
    }
}


class T1467_Picture extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 50);
    }

    public function setUp()
    {
        $this->hasMany('T1467_Item as Items', array(
            'refClass' => 'T1467_ItemPicture',
            'local' => 'picture_id',
            'foreign' => 'item_id'
        ));
    }
}


class T1467_Puzzle extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 50);
    }

    public function setUp()
    {
        $this->hasMany('T1467_Item as Items', array(
            'refClass' => 'T1467_ItemPicture',
            'local' => 'puzzle_id',
            'foreign' => 'item_id'
        ));
    }
}


class T1467_ItemPicture extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('item_id', 'integer', null, array('primary' => true));
        $this->hasColumn('picture_id', 'integer', null, array('primary' => true));
    }
}


class T1467_ItemPuzzle extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('item_id', 'integer', null, array('primary' => true));
        $this->hasColumn('puzzle_id', 'integer', null, array('primary' => true));
    }
}