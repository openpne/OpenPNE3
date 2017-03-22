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
 * Doctrine_Ticket_Ayoub_TestCase
 *
 * @package     Doctrine
 * @author      pascal borreli
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_Ayoub_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_Ayoub_Sura';
        $this->tables[] = 'Ticket_Ayoub_Place';
        parent::prepareTables();
    }

    public function testTest()
    {
        $name = 'Sura Name';
        $placeName = 'Place Name';

        try {
            $sura = new Ticket_Ayoub_Sura;
            $sura->Translation['transliteration']->name = $name;
            $sura->Ticket_Ayoub_Place->Translation['transliteration']->name = $placeName;
            $sura->Ticket_Ayoub_Place->state('TDIRTY');
            $sura->save();
            $reopened = Doctrine_Core::getTable('Ticket_Ayoub_Sura')->findOneById($sura->id);
            $this->assertEqual($name, $reopened->Translation['transliteration']->name);
            $this->assertEqual(1, $reopened->place_id);
            $this->assertEqual($placeName, $reopened->Ticket_Ayoub_Place->Translation['transliteration']->name);

            $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Ticket_Ayoub_Sura extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('Ticket_Ayoub_Sura');
    $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('revelation_order', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('is_mekka', 'boolean', null, array('type' => 'boolean'));
    $this->hasColumn('ayat_number', 'integer', null, array('type' => 'integer'));
    $this->hasColumn('place_id', 'integer', null, array('type' => 'integer'));

    $this->option('collate', 'utf8_unicode_ci');
    $this->option('charset', 'utf8');
  }

  public function setUp()
  {
    $this->hasOne('Ticket_Ayoub_Place', array('local' => 'place_id',
                                 'foreign' => 'id'));

    $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'name'), 'length' => '20'));
    $this->actAs($i18n0);
  }
}

class Ticket_Ayoub_Place extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('Ticket_Ayoub_Place');
    $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 255, array('type' => 'string', 'length' => '255'));

    $this->option('collate', 'utf8_unicode_ci');
    $this->option('charset', 'utf8');
  }

  public function setUp()
  {
    $this->hasMany('Ticket_Ayoub_Sura', array('local' => 'id',
                                 'foreign' => 'place_id'));

    $i18n0 = new Doctrine_Template_I18n(array('fields' => array(0 => 'name'), 'length' => '20'));
    $this->actAs($i18n0);
  }
}