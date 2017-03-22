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
 * Doctrine_Migration_Base_TestCase
 * 
 * @package     Doctrine
 * @author      Dan Bettles <danbettles@yahoo.co.uk>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Migration_Base_TestCase extends Doctrine_UnitTestCase 
{
    public function setUp() {}

    public function testIsAbstract()
    {
        $reflectionClass = new ReflectionClass('Doctrine_Migration_Base');
        $this->assertTrue($reflectionClass->isAbstract());
    }

    public function testByDefaultHasNoDefaultTableOptions()
    {
        $this->assertEqual(array(), Doctrine_Migration_Base::getDefaultTableOptions());
    }

    public function testGetdefaulttableoptionsReturnsTheOptionsSetWithSetdefaulttableoptions()
    {
        $fixtures = array(
            array(array('charset' => 'utf8')),
            array(array()),
            array('type' => 'INNODB', 'charset' => 'utf8', 'collate' => 'utf8_unicode_ci'),
        );

        foreach ($fixtures as $fixture) {
            Doctrine_Migration_Base::setDefaultTableOptions($fixture);
            $this->assertEqual($fixture, Doctrine_Migration_Base::getDefaultTableOptions());
        }
    }

    public function tearDown()
    {
        Doctrine_Migration_Base::setDefaultTableOptions(array());
    }

    public function testCreatetableMergesTheDefaultTableOptionsWithTheSpecifiedOptions()
    {
        $fixtures = array(
            array(
                'default' => array('type' => 'INNODB', 'charset' => 'utf8', 'collate' => 'utf8_unicode_ci'),
                'user' => array(),
                'expected' => array('type' => 'INNODB', 'charset' => 'utf8', 'collate' => 'utf8_unicode_ci'),
            ),
            array(
                'default' => array('type' => 'INNODB', 'charset' => 'utf8', 'collate' => 'utf8_unicode_ci'),
                'user' => array('charset' => 'latin1', 'collate' => 'latin1_general_ci'),
                'expected' => array('type' => 'INNODB', 'charset' => 'latin1', 'collate' => 'latin1_general_ci'),
            ),
        );

        foreach ($fixtures as $fixture) {
            Doctrine_Migration_Base_TestCase_TestBase01::setDefaultTableOptions($fixture['default']);
            $migration = new Doctrine_Migration_Base_TestCase_TestBase01();
            $migration->createTable('anything', array(), $fixture['user']);
            $this->assertEqual($fixture['expected'], $migration->mergedOptions);
        }
    }
}

class Doctrine_Migration_Base_TestCase_TestBase01 extends Doctrine_Migration_Base
{
    public $mergedOptions = array();

    public function table($upDown, $tableName, array $fields = array(), array $options = array())
    {
        $this->mergedOptions = $options;
    }
}
