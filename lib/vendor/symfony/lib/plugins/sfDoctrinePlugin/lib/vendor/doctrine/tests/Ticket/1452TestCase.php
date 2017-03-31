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
 * Doctrine_Ticket_1452_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1452_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'Model_Product';
        parent::prepareTables();
    }

    public function testFindByIdAutomaticallyLoadsRelationsForInstanceTranslation()
    {
        $name = 'New York';
        $description = 'En dansk beskrivelse';

        try {
            $item = new Model_Product;
            $item->name = $name;
            $item->Translation['DK']->description = $description;
            $item->Translation['EN']->description = 'Some english description';
            $item->save();

            $reopened = Doctrine_Core::getTable('Model_Product')->findOneById($item->id);
            $this->assertEqual($name, $reopened->name);
            $this->assertEqual($description, $reopened->Translation['DK']->description);

            $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Model_Product extends Doctrine_Record
{
    public function setTableDefinition ()
    {
        $this->hasColumn('name', 'string', 30);
        $this->hasColumn('description', 'string', 65555);
        $this->hasColumn('price', 'integer', 20);
    }

    public function setUp ()
    {
        $this->actAs('I18n', array('fields' => array('description')));
    }
}