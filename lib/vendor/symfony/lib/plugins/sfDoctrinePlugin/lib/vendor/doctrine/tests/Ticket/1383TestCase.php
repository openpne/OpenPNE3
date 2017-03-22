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
 * Doctrine_Ticket_1383_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1383_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1383_Image';
        $this->tables[] = 'Ticket_1383_Brand_Image';
        $this->tables[] = 'Ticket_1383_Brand';
        parent::prepareTables();
    }

    public function testTest()
    {
        $orig = Doctrine_Manager::getInstance()->getAttribute(Doctrine_Core::ATTR_VALIDATE);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        try {
            $brand = new Ticket_1383_Brand;
            $brand->name = 'The Great Brand';
            $brand->Ticket_1383_Brand_Image[0]->name = 'imagename';
            $brand->Ticket_1383_Brand_Image[0]->owner_id = 1;
            $brand->Ticket_1383_Brand_Image[0]->owner_type = 0;
            $brand->save();
            $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, $orig);
    }
}

class Ticket_1383_Image extends Doctrine_Record
{
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('owner_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('owner_type', 'integer', 5, array('notnull' => true));
        $this->hasColumn('name', 'string', 128, array('notnull' => true, 'unique' => true));

        $this->setSubclasses(array(
            'Ticket_1383_Brand_Image'           => array('owner_type' => 0)
        ));
    }
}

class Ticket_1383_Brand_Image extends Ticket_1383_Image
{
}

class Ticket_1383_Brand extends Doctrine_Record
{
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 255, array('notnull' => true));
    }
    
    public function setUp() {
        $this->hasMany(
            'Ticket_1383_Brand_Image',
            array(
                'local' => 'id',
                'foreign' => 'owner_id'
            )
        );
    }
}