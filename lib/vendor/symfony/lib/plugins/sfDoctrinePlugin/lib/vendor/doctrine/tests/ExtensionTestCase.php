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
 * Doctrine_Extension_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Extension_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        Doctrine_Core::setExtensionsPath(dirname(__FILE__).'/Extension');
        spl_autoload_register(array('Doctrine_Core', 'extensionsAutoload'));

        Doctrine_Manager::getInstance()
            ->registerExtension('TestExtension');

        $this->tables[] = 'ExtensionBehaviorTest';
        parent::prepareTables();
    }

    public function testExtensionAutoload()
    {
        $this->assertTrue(class_exists('Doctrine_Test'));
    }

    public function testBehaviorExtension()
    {
        $test = Doctrine_Core::getTable('ExtensionBehaviorTest');
        $this->assertTrue($test->hasColumn('testing'));
        $this->assertTrue($test->hasColumn('test'));
    }

    public function tearDown()
    {
        spl_autoload_unregister(array('Doctrine_Core', 'extensionsAutoload'));
    }
}

class ExtensionBehaviorTest extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('testing', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('TestBehavior');
    }
}
