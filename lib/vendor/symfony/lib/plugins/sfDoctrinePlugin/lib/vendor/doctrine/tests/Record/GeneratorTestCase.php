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
 * Doctrine_Record_Generator_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Record_Generator_TestCase extends Doctrine_UnitTestCase 
{
    public function testGeneratorComponentBinding()
    {
        Doctrine_Manager::connection('sqlite::memory:', 'test_tmp_conn', false);
        Doctrine_Manager::getInstance()->bindComponent('I18nGeneratorComponentBinding', 'test_tmp_conn');
        Doctrine_Core::createTablesFromArray(array('I18nGeneratorComponentBinding'));

        try {
            $i = new I18nGeneratorComponentBinding();
            $i->name = 'test';
            $i->Translation['EN']->title = 'en test';
            $i->Translation['FR']->title = 'fr test';
            $i->save();
            
            $this->pass();
            
            $this->assertTrue($i->id > 0);
            $this->assertEqual($i->Translation['EN']->title, 'en test');
            $this->assertEqual($i->Translation['FR']->title, 'fr test');
            $this->assertEqual($i->getTable()->getConnection()->getName(), $i->Translation->getTable()->getConnection()->getName());
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class I18nGeneratorComponentBinding extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('title', 'string');
    }
    
    public function setUp()
    {
        $this->actAs('I18n', array('fields' => array('title')));
    }
}