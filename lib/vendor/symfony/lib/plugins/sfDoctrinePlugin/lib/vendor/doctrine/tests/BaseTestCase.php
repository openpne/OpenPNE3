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
 * Doctrine_Base_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Base_TestCase extends Doctrine_UnitTestCase 
{
    public function testAggressiveModelLoading()
    {
        $path = realpath('ModelLoadingTest/Aggressive');
        
        $models = Doctrine_Core::loadModels($path, Doctrine_Core::MODEL_LOADING_AGGRESSIVE);

        // Ensure the correct model names were returned
        $this->assertTrue(isset($models['AggressiveModelLoadingUser']) && $models['AggressiveModelLoadingUser'] == 'AggressiveModelLoadingUser');
        $this->assertTrue(isset($models['AggressiveModelLoadingProfile']) && $models['AggressiveModelLoadingProfile'] == 'AggressiveModelLoadingProfile');
        $this->assertTrue(isset($models['AggressiveModelLoadingContact']) && $models['AggressiveModelLoadingContact'] == 'AggressiveModelLoadingContact');

        // Make sure it does not include the base classes
        $this->assertTrue( ! isset($models['BaseAggressiveModelLoadingUser']));
        
        $filteredModels = Doctrine_Core::filterInvalidModels($models);

        // Make sure filterInvalidModels filters out base abstract classes
        $this->assertTrue( ! isset($models['BaseAggressiveModelLoadingUser']));
    }

    public function testConservativeModelLoading()
    {
        $path = realpath('ModelLoadingTest/Conservative');

        $models = Doctrine_Core::loadModels($path, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);

        $this->assertTrue( ! class_exists('ConservativeModelLoadingUser', false));
        $this->assertTrue( ! class_exists('ConservativeModelLoadingProfile', false));
        $this->assertTrue( ! class_exists('ConservativeModelLoadingContact', false));
        $this->assertTrue( ! class_exists('BaseConservativeModelLoadingUser', false));
    }

    public function testAllModelsAvailable()
    {
        // Ensure models were loaded
        $this->assertTrue(class_exists('AggressiveModelLoadingUser'));
        $this->assertTrue(class_exists('AggressiveModelLoadingProfile'));
        $this->assertTrue(class_exists('AggressiveModelLoadingContact'));
        $this->assertTrue(class_exists('BaseAggressiveModelLoadingUser'));

        $this->assertTrue( class_exists('ConservativeModelLoadingUser', true));
        $this->assertTrue( class_exists('ConservativeModelLoadingProfile', true));
        $this->assertTrue( class_exists('ConservativeModelLoadingContact', true));
        $this->assertTrue( class_exists('BaseConservativeModelLoadingUser', true));
    }

    public function testModelLoadingCacheInformation()
    {
        $models = Doctrine_Core::getLoadedModels();

        $this->assertTrue(in_array('AggressiveModelLoadingUser', $models));
        $this->assertTrue(in_array('ConservativeModelLoadingProfile', $models));
        $this->assertTrue(in_array('ConservativeModelLoadingContact', $models));
        
        $modelFiles = Doctrine_Core::getLoadedModelFiles();
        $this->assertTrue(file_exists($modelFiles['ConservativeModelLoadingUser']));
        $this->assertTrue(file_exists($modelFiles['ConservativeModelLoadingProfile']));
        $this->assertTrue(file_exists($modelFiles['ConservativeModelLoadingContact']));
    }

    public function testGetConnectionByTableName()
    {
        $connectionBefore = Doctrine_Core::getConnectionByTableName('entity');

        Doctrine_Manager::connection('sqlite::memory:', 'test_memory');
        Doctrine_Manager::getInstance()->bindComponent('Entity', 'test_memory');

        $connectionAfter = Doctrine_Core::getConnectionByTableName('entity');

        $this->assertEqual($connectionAfter->getName(), 'test_memory');

        Doctrine_Manager::getInstance()->bindComponent('Entity', $connectionBefore->getName());

        $connectionAfter = Doctrine_Core::getConnectionByTableName('entity');
        
        $this->assertEqual($connectionBefore->getName(), $connectionAfter->getName());
    }
}