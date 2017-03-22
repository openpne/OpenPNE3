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
 * Doctrine_Hydrate_Driver_TestCase
 *
 * @package     Doctrine
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$
 */
class Doctrine_Hydrate_Driver_TestCase extends Doctrine_UnitTestCase
{
    public function testCustomHydrator()
    {
        Doctrine_Manager::getInstance()
            ->registerHydrator('MyHydrator', 'MyHydrator');

        $result = Doctrine_Core::getTable('User')
            ->createQuery('u')
            ->execute(array(), 'MyHydrator');

        $this->assertEqual($result, 'MY_HYDRATOR');
    }

    public function testCustomHydratorUsingClassInstance()
    {
        $hydrator = new MyHydrator();
        Doctrine_Manager::getInstance()
            ->registerHydrator('MyHydrator', $hydrator);

        $result = Doctrine_Core::getTable('User')
            ->createQuery('u')
            ->execute(array(), 'MyHydrator');

        $this->assertEqual($result, 'MY_HYDRATOR');
    }

    public function testCustomHydratorConstructor()
    {
        $queryComponents = array('queryComponents');
        $tableAliases = array('tableAliases');
        $hydrationMode = array('hydrationMode');

        $hydrator = new MyHydrator($queryComponents, $tableAliases, $hydrationMode);

        $this->assertEqual($queryComponents, $hydrator->_queryComponents);
        $this->assertEqual($tableAliases, $hydrator->_tableAliases);
        $this->assertEqual($hydrationMode, $hydrator->_hydrationMode);
    }

    public function testCustomHydratorUsingClassInstanceExceptingException()
    {
        $hydrator = new StdClass();
        Doctrine_Manager::getInstance()
            ->registerHydrator('MyHydrator', $hydrator);

        try {
             Doctrine_Core::getTable('User')
                ->createQuery('u')
                ->execute(array(), 'MyHydrator');

            $this->fail('Expected exception not thrown: Doctrine_Hydrator_Exception');
        } catch (Doctrine_Hydrator_Exception $e) {
        }
    }
}

class MyHydrator extends Doctrine_Hydrator_Abstract
{
    public $_queryComponents;
    public $_tableAliases;
    public $_hydrationMode;

    public function hydrateResultSet($stmt)
    {
        return 'MY_HYDRATOR';
    }
}