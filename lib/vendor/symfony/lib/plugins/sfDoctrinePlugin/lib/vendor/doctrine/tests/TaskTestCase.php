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
 * Doctrine_Task_TestCase
 * 
 * N.B. Invalid task classes are loaded just-in-time to avoid clashes with the CLI tests.  Other test-specific
 * subclasses are declared at the bottom of this file.
 *
 * @package     Doctrine
 * @author      Dan Bettles <danbettles@yahoo.co.uk>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Task_TestCase extends Doctrine_UnitTestCase 
{
    public function setUp() {}

    public function tearDown() {}

    public function testDerivetasknameReturnsTheNameOfATaskFromItsClassName()
    {
        $this->assertEqual('migrate', Doctrine_Task::deriveTaskName('Doctrine_Task_Migrate'));
        $this->assertEqual('create-db', Doctrine_Task::deriveTaskName('Doctrine_Task_CreateDb'));
        $this->assertEqual('generate-models-db', Doctrine_Task::deriveTaskName('Doctrine_Task_GenerateModelsDb'));
        $this->assertEqual('custom-task', Doctrine_Task::deriveTaskName('CustomTask'));

        /*
         * PHP 5.3-specific tests
         * 
         * One would hope that authors of custom tasks would name their tasks manually, but since we can't guarantee
         * anything, we'll have to _try_ to create a sensible name
         */
        $this->assertEqual('fully-qualified-custom-task', Doctrine_Task::deriveTaskName('fully\qualified\CustomTask'));
        //$this->assertEqual('fully-qualified-custom-task', Doctrine_Task::deriveTaskName('fully\qualified\Doctrine_Task_CustomTask'));
    }

    public function testNameByDefaultIsDerivedFromTheNameOfTheClass()
    {
        $oTask = new Doctrine_Task_TestCase_TestTask001();
        $this->assertEqual('test-case--test-task001', $oTask->taskName);  /*@todo Temporary, maybe*/
        $this->assertEqual('test-case--test-task001', $oTask->getTaskName());
    }

    public function testSettasknameSetsTheNameOfTheTask()
    {
        $oTask = new Doctrine_Task_TestCase_TestTask002();
        $this->assertEqual('better-task-name', $oTask->getTaskName());
    }

    /**
     * Loads a PHP fixture from the directory for this test case
     * 
     * @ignore
     * @param string $basename
     */
    protected function loadPhpFixture($basename)
    {
        require_once(dirname(__FILE__) . '/TaskTestCase/' . $basename);
    }

    public function testSettasknameThrowsAnExceptionIfTheTaskNameIsInvalid()
    {
        $this->loadPhpFixture('TestTask006.php');

        try {
            new Doctrine_Task_TestCase_TestTask006();
        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() == 'The task name "invalid_task_name", in Doctrine_Task_TestCase_TestTask006, is invalid') {
                $this->pass();
                return;
            }
        }

        $this->fail();
    }

    //This gives us a way to set an alternate task name that's in keeping with how we currently set-up tasks
    public function testDoesNotAutomaticallySetTheNameOfTheTaskIfItWasSetManually()
    {
        $oTask = new Doctrine_Task_TestCase_TestTask003();
        $this->assertEqual('better-task-name', $oTask->getTaskName());
    }

    public function testThrowsAnExceptionIfTheTaskNameIsInvalid()
    {
        $this->loadPhpFixture('TestTask004.php');
        $this->loadPhpFixture('TestTask005.php');

        $aClassWithInvalidTaskName = array(
            'Doctrine_Task_TestCase_TestTask004' => '-invalid-task-name',
            'Doctrine_Task_TestCase_TestTask005' => 'another invalid task name',
        );

        $numPasses = 0;

        foreach ($aClassWithInvalidTaskName as $classWithInvalidTaskName => $invalidTaskName) {
            try {
                new $classWithInvalidTaskName();
            } catch (InvalidArgumentException $e) {
                if ($e->getMessage() == "The task name \"{$invalidTaskName}\", in {$classWithInvalidTaskName}, is invalid") {
                    $numPasses++;
                }
            }
        }

        if ($numPasses == count($aClassWithInvalidTaskName)) {
            $this->pass();
        }
        else {
            $this->fail();
        }
    }
}

class Doctrine_Task_TestCase_TestTask001 extends Doctrine_Task
{
    public function execute() {}
}

class Doctrine_Task_TestCase_TestTask002 extends Doctrine_Task
{
    public function __construct()
    {
        $this->setTaskName('better-task-name');
    }

    public function execute() {}
}

class Doctrine_Task_TestCase_TestTask003 extends Doctrine_Task
{
    public $taskName = 'better-task-name';

    public function execute() {}
}