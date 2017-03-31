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
 * Doctrine_Ticket_2375_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_2375_TestCase extends Doctrine_UnitTestCase
{
    public function testTest()
    {
        $models1Dir = dirname(__FILE__) . '/2375/models1';
        $models2Dir = dirname(__FILE__) . '/2375/models2';

        // try loading a couple initial models

        $models1 = Doctrine_Core::loadModels($models1Dir);
        //$models2 = Doctrine_Core::loadModels($models2Dir);

        // make sure two models were loaded
        $this->assertEqual(2, count($models1));

        // make sure the right models were loaded
        $this->assertTrue(key_exists('Ticket_2375_Model1', $models1));
        $this->assertTrue(key_exists('Ticket_2375_Model2', $models1));

        // get a list of all models that have been loaded
        $loadedModels = Doctrine_Core::getLoadedModelFiles();

        // make sure the paths are correct
        $this->assertEqual($loadedModels['Ticket_2375_Model1'], $models1Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model1.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model2'], $models1Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model2.php');

        // try loading a few more models

        $models2 = Doctrine_Core::loadModels($models2Dir);

        // make sure the right models were loaded
        $this->assertTrue(key_exists('Ticket_2375_Model3', $models2));
        $this->assertTrue(key_exists('Ticket_2375_Model4', $models2));
        $this->assertTrue(key_exists('Ticket_2375_Model5', $models2));
        $this->assertTrue(key_exists('Ticket_2375_Model6', $models2));

        // get a list of all models that have been loaded
        $loadedModels = Doctrine_Core::getLoadedModelFiles();

        // make sure the paths are correct
        $this->assertEqual($loadedModels['Ticket_2375_Model1'], $models1Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model1.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model2'], $models1Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model2.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model3'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model3.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model4'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model4.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model5'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model5.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model6'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model5.php');

        // try loading the first models again

        $models1 = Doctrine_Core::loadModels($models1Dir);

        // make sure the right models were loaded
        $this->assertTrue(key_exists('Ticket_2375_Model1', $models1));
        $this->assertTrue(key_exists('Ticket_2375_Model2', $models1));

        // get a list of all models that have been loaded
        $loadedModels = Doctrine_Core::getLoadedModelFiles();

        // make sure the paths are correct
        $this->assertEqual($loadedModels['Ticket_2375_Model1'], $models1Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model1.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model2'], $models1Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model2.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model3'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model3.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model4'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model4.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model5'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model5.php');
        $this->assertEqual($loadedModels['Ticket_2375_Model6'], $models2Dir . DIRECTORY_SEPARATOR . 'Ticket_2375_Model5.php');
    }
}