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
 * Doctrine_Ticket_DC147_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC147_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'DC147_Product';
        $this->tables[] = 'DC147_Site';
        $this->tables[] = 'DC147_Multiple';
        $this->tables[] = 'DC147_MultipleValue';
        parent::prepareTables();
    }

    public function testInlineMultiple()
    {
        $yml = <<<END
---
DC147_Multiple:
  ISBN2:
    name: isbn2
  ISBN3:
    name: isbn3
DC147_Product: 
  Product_1: 
    name: book3
    MultipleValues:
      Multi_1:
        value: 123345678
        Multiple: ISBN2
      Multi_2:
        value: 232323233
        Multiple: ISBN3
  Product_2: 
    name: book4
    MultipleValues:
      Multi_3:
        value: 444455555
        Multiple: ISBN2
      Multi_4:
        value: 232323233
        Multiple: ISBN3
END;
        try {
            file_put_contents('test.yml', $yml);
            Doctrine_Core::loadData('test.yml', true);

            $this->conn->clear();

            $query = new Doctrine_Query();
            $query->from('DC147_Product p, p.MultipleValues v, v.Multiple m')
                ->where('p.name = ?', 'book3');

            $product = $query->fetchOne();

            $this->assertEqual($product->name, 'book3');
            $this->assertEqual($product->MultipleValues->count(), 2);
            $this->assertEqual($product->MultipleValues[0]->value, '123345678');
            $this->assertEqual(is_object($product->MultipleValues[0]->Multiple), true);
            $this->assertEqual($product->MultipleValues[0]->Multiple->name, 'isbn2');

            $query = new Doctrine_Query();
            $query->from('DC147_Product p, p.MultipleValues v, v.Multiple m')
                ->where('p.name = ?', 'book4');

            $product = $query->fetchOne();

            $this->assertEqual($product->name, 'book4');
            $this->assertEqual($product->MultipleValues->count(), 2);
            $this->assertEqual($product->MultipleValues[0]->value, '444455555');
            $this->assertEqual($product->MultipleValues[1]->value, '232323233');
            $this->assertEqual(is_object($product->MultipleValues[0]->Multiple), true);
            $this->assertEqual(is_object($product->MultipleValues[1]->Multiple), true);
            $this->assertEqual($product->MultipleValues[0]->Multiple->name, 'isbn2');
            $this->assertEqual($product->MultipleValues[1]->Multiple->name, 'isbn3');

            $this->pass();
        } catch (Exception $e) {
            $this->fail();
        }

        unlink('test.yml');
    }
}

class DC147_Product extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('site_id', 'integer', null, array('type' => 'integer'));
        $this->hasColumn('name', 'string', 255, array('type' => 'string', 'notnull' => true, 'length' => '255'));
    }

    public function setUp()
    {
        $this->hasOne('DC147_Site as Site', array('local' => 'site_id',
                                    'foreign' => 'id'));
        $this->hasMany('DC147_MultipleValue as MultipleValues', array('local' => 'id',
                                                              'foreign' => 'product_id'));
    }
}
class DC147_Site extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255, array('type' => 'string', 'length' => '255'));
    }

    public function setUp()
    {
        $this->hasMany('DC147_Product as Products', array('local' => 'id',
                                        'foreign' => 'site_id'));
    }
}
class DC147_Multiple extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255, array('type' => 'string', 'notnull' => true, 'length' => '255'));
    }

    public function setUp()
    {
        $this->hasMany('DC147_MultipleValue as MultipleValues', array('local' => 'id',
                                                 'foreign' => 'multiple_id'));
    }
}
class DC147_MultipleValue extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('product_id', 'integer', null, array('type' => 'integer', 'primary' => true));
        $this->hasColumn('multiple_id', 'integer', null, array('type' => 'integer', 'primary' => true));
        $this->hasColumn('value', 'clob', null, array('type' => 'clob'));
    }

    public function setUp()
    {
        $this->hasOne('DC147_Multiple as Multiple', array('local' => 'multiple_id',
                                                       'foreign' => 'id'));

        $this->hasOne('DC147_Product as Product', array('local' => 'product_id',
                                        'foreign' => 'id'));
    }
}