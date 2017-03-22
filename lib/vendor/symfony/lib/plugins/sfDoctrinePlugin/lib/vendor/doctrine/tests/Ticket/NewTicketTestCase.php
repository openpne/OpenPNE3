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
 * Doctrine_I18nRelation_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_NewTicket_TestCase extends Doctrine_UnitTestCase
{
    private $p1;
    private $p2;

    public function prepareData()
    {
        $ad1 = new Testing_AttributeDefinition();
        $ad1['name'] = 'price';
        $ad1->save();
        $ad2 = new Testing_AttributeDefinition();
        $ad2['name'] = 'quantity';
        $ad2->save();

        $a1 = new Testing_Attribute();
        $a1['Definition'] = $ad1;
        $a1['value'] = '5.00';
        $a2 = new Testing_Attribute();
        $a2['Definition'] = $ad2;
        $a2['value'] = '500';

        $p1 = new Testing_Product();
        $p1['name'] = 'Basketball';
        $p1->save();
        $this->p1 = $p1;
        $p2 = new Testing_Product();
        $p2['name'] = 'Football';
        $p2->save();
        $this->p2 = $p2;

        $pa1 = new Testing_ProductAttribute();
        $pa1['Product'] = $p1;
        $pa1['Attribute'] = $a1;
        $pa1->save();
        $pa2 = new Testing_ProductAttribute();
        $pa2['Product'] = $p1;
        $pa2['Attribute'] = $a2;
        $pa2->save();
    }

    public function prepareTables()
    {
        $this->tables = array('Testing_AttributeDefinition','Testing_Attribute', 'Testing_Product',
                'Testing_ProductAttribute');
        parent::prepareTables();
    }

    public function testTicket()
    {
        $q1 = new Doctrine_Query();
        $q1->select('p.*');
        $q1->from('Testing_Product p');
        $q1->where('p.id = ?', $this->p1['id']);
        $q1->addSelect('a.*, ad.*');
        $q1->leftJoin('p.Attributes a');
        $q1->leftJoin('a.Definition ad');

        $q2 = new Doctrine_Query();
        $q2->select('p.*');
        $q2->from('Testing_Product p');
        $q2->where('p.id = ?', $this->p2['id']);
        $q2->addSelect('a.*, ad.*');
        $q2->leftJoin('p.Attributes a');
        $q2->leftJoin('a.Definition ad');

        // This query works perfect
        $r1 = $q1->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        //var_dump($r1);
        // This query throws an exception!!!
        $r2 = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        //$r2 = $q2->execute();
        //var_dump($r2);
    }
   
}

class Testing_AttributeDefinition extends Doctrine_Record
{
        public function setTableDefinition()
    {
        $this->setTableName('testing__attribute_definitions');
                $this->hasColumn('id', 'integer', 4,
                    array('primary'=>true,'autoincrement'=>true));
                $this->hasColumn('name', 'string', 64, array('notnull'=>true));
    }
    public function setUp()
    {
    }
}

class Testing_Attribute extends Doctrine_Record
{
        public function setTableDefinition()
    {
        $this->setTableName('testing__attributes');

                $this->hasColumn('id', 'integer', 4,
                    array('primary'=>true,'autoincrement'=>true));
                $this->hasColumn('attribute_definition_id', 'integer', 4,
                    array('notnull'=>true));
                $this->hasColumn('value', 'string', 255, array('notnull'=>false));
    }
    public function setUp()
    {
        $this->hasOne(
                'Testing_AttributeDefinition as Definition',
                array(
                                'local'   => 'attribute_definition_id',
                                'foreign' => 'id',
                                'onDelete' => 'CASCADE'
                )
        );
    }
}

class Testing_Product extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('testing__products');
                $this->hasColumn('id', 'integer', 4,
                    array('primary'=>true,'autoincrement'=>true));
        $this->hasColumn('name', 'string', 40,
            array('notnull'=>true));
    }
    public function setUp()
    {

                $this->hasMany(
                'Testing_Attribute as Attributes',
                array(
                        'local'    => 'product_id',
                        'foreign'  => 'attribute_id',
                        'refClass' => 'Testing_ProductAttribute',
                                'onDelete' => 'CASCADE',
                        )
                );
    }
}

class Testing_ProductAttribute extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('testing__products_attributes');

        $this->hasColumn('product_id', 'integer', 4, array('primary'=>true,
            'notnull'=>true));
        $this->hasColumn('attribute_id', 'integer', 4,
            array('primary'=>true, 'notnull'=>true));
    }
    public function setUp()
    {
        $this->hasOne(
                'Testing_Product as Product',
                array(
                        'local'    => 'product_id',
                        'foreign'  => 'id',
                                'onDelete' => 'CASCADE'
                )
        );
        $this->hasOne(
                'Testing_Attribute as Attribute',
                array(
                        'local'    => 'attribute_id',
                        'foreign'  => 'id',
                                'onDelete' => 'RESTRICT'
                )
        );
    }

}