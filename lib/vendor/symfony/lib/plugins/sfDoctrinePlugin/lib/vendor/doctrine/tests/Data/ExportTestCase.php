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
 * Doctrine_Data_Export_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Data_Export_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'I18nTestExport';
        parent::prepareTables();
    }

    public function testI18nExport()
    {
        try {
            $i = new I18nTestExport();
            $i->Translation['en']->title = 'english test';
            $i->Translation['fr']->title = 'french test';
            $i->test_object = new stdClass();
            $i->save();

            $data = new Doctrine_Data();
            $data->exportData('test.yml', 'yml', array('I18nTestExport', 'I18nTestExportTranslation'));

            $array = Doctrine_Parser::load('test.yml', 'yml');

            $this->assertTrue( ! empty($array));
            $this->assertTrue(isset($array['I18nTestExport']['I18nTestExport_1']));
            $this->assertTrue(isset($array['I18nTestExportTranslation']['I18nTestExportTranslation_1_en']));
            $this->assertTrue(isset($array['I18nTestExportTranslation']['I18nTestExportTranslation_1_fr']));

            $i->Translation->delete();
            $i->delete();

            Doctrine_Core::loadData('test.yml');

            $q = Doctrine_Query::create()
                ->from('I18nTestExport e')
                ->leftJoin('e.Translation t');

            $results = $q->execute();
            $this->assertEqual(get_class($results[0]->test_object), 'stdClass');

            $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        if (file_exists('test.yml')) {
            unlink('test.yml');
        }
    }
}

class I18nTestExport extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 200);
        $this->hasColumn('title', 'string', 200);
        $this->hasColumn('test_object', 'object');
    }

    public function setUp()
    {
        $this->actAs('I18n', array('fields' => array('name', 'title')));
    }
}