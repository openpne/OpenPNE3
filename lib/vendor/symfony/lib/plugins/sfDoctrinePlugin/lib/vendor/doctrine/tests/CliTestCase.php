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
 * Doctrine_Cli_TestCase
 *
 * @package     Doctrine
 * @author      Dan Bettles <danbettles@yahoo.co.uk>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Cli_TestCase extends Doctrine_UnitTestCase 
{
    /**
     * @ignore
     * @var string
     */
    protected $fixturesPath;

    /**
     * The names of some of the Doctrine Task classes
     * 
     * @ignore
     * @var array
     */
    protected $doctrineTaskClassName = array(
        'Doctrine_Task_CreateDb' => 'create-db',
        'Doctrine_Task_Migrate' => 'migrate',
        'Doctrine_Task_GenerateModelsDb' => 'generate-models-db',
    );

    /**
     * @ignore
     * @return string
     */
    protected function getFixturesPath()
    {
        if (! isset($this->fixturesPath)) {
            $this->fixturesPath = dirname(__FILE__) . '/CliTestCase';
        }

        return $this->fixturesPath;
    }

    public function setUp() {}

    public function tearDown() {}

    public function testTheNameOfTheTaskBaseClassNameIsStoredInAClassConstant()
    {
        $this->assertFalse(is_null(constant('Doctrine_Cli::TASK_BASE_CLASS')));
    }

    public function testGetconfigReturnsTheArrayUsedToConstructTheInstance()
    {
        $config = array('foo' => 'bar', 'baz' => 'bip');
        $cli = new Doctrine_Cli_TestCase_PassiveCli($config);
        $this->assertEqual($config, $cli->getConfig());
    }

    public function testIsConstructedWithAnEmptyConfigArrayByDefault()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();
        $this->assertEqual(array(), $cli->getConfig());
    }

    public function testGetconfigReturnsTheArraySetWithSetconfig()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();

        $this->assertEqual(array(), $cli->getConfig());

        $config = array('foo' => 'bar', 'baz' => 'bip');
        $cli->setConfig($config);
        $this->assertEqual($config, $cli->getConfig());
    }

    public function testGetformatterReturnsTheFormatterUsedToConstructTheInstance()
    {
        $formatter = new Doctrine_Cli_Formatter();
        $cli = new Doctrine_Cli_TestCase_PassiveCli(array(), $formatter);
        $this->assertIdentical($formatter, $cli->getFormatter());
    }

    public function testIsConstructedWithAnAnsiColourFormatterByDefault()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();
        $this->assertTrue($cli->getFormatter() instanceof Doctrine_Cli_AnsiColorFormatter);
    }

    public function testGetformatterReturnsTheFormatterSetWithSetformatter()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();
        $formatter = new Doctrine_Cli_Formatter();
        $cli->setFormatter($formatter);
        $this->assertIdentical($formatter, $cli->getFormatter());
    }

    public function testHasconfigvalueReturnsTrueIfTheElementInTheConfigHasTheSpecifiedValue()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli(array('foo' => 'bar', 'true' => true));

        $this->assertTrue($cli->hasConfigValue('foo', 'bar'));
        $this->assertFalse($cli->hasConfigValue('foo', 'baz'));

        $this->assertTrue($cli->hasConfigValue('true', 1));
        $this->assertTrue($cli->hasConfigValue('true', true));
        $this->assertFalse($cli->hasConfigValue('true', 1, true));
        $this->assertTrue($cli->hasConfigValue('true', true, true));

        $this->assertFalse($cli->hasConfigValue('missing', 'anything'));
    }

    public function testHasconfigvalueReturnsTrueIfTheElementInTheConfigIsSetAndAValueWasNotSpecified()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli(array('foo' => 'bar'));
        $this->assertTrue($cli->hasConfigValue('foo'));
        $this->assertFalse($cli->hasConfigValue('baz'));
    }

    public function testGetconfigvalueReturnsTheValueOfTheSpecifiedElementInTheConfig()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli(array('foo' => 'bar'));
        $this->assertEqual('bar', $cli->getConfigValue('foo'));
    }

    public function testGetconfigvalueThrowsAnExceptionIfTheSpecifiedElementDoesNotExistInTheConfig()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();

        $key = 'anything';

        try {
            $cli->getConfigValue($key);
        } catch (OutOfBoundsException $e) {
            if ($e->getMessage() == "The element \"{$key}\" does not exist in the config") {
                $this->pass();
                return;
            }
        }

        $this->fail();
    }

    public function testGetconfigvalueReturnsTheDefaultValueIfTheSpecifiedElementDoesNotExistInTheConfig()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();
        $this->assertEqual('default', $cli->getConfigValue('anything', 'default'));
    }

    public function testGetregisteredtasksReturnsTheArraySetWithSetregisteredtasks()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();

        $this->assertEqual(array(), $cli->getRegisteredTasks());

        $registeredTask = array('Doctrine_Task_CreateDb' => 'anything');
        $cli->setRegisteredTasks($registeredTask);
        $this->assertEqual($registeredTask, $cli->getRegisteredTasks());
    }

    public function testTaskclassisregisteredReturnsTrueIfTheSpecifiedClassIsRegistered()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();

        $this->assertFalse($cli->taskClassIsRegistered('Doctrine_Task_CreateDb'));
        $this->assertFalse($cli->taskClassIsRegistered('Doctrine_Task_DropDb'));

        $cli->setRegisteredTasks(array('Doctrine_Task_CreateDb' => 'anything'));
        $this->assertTrue($cli->taskClassIsRegistered('Doctrine_Task_CreateDb'));
        $this->assertFalse($cli->taskClassIsRegistered('Doctrine_Task_DropDb'));
    }

    public function testTasknameisregisteredReturnsTrueIfATaskWithTheSpecifiedNameIsRegistered()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();

        $expectedClassName = 'Doctrine_Cli_TestCase_TestTask01';
        $task = new $expectedClassName();
        $expectedTaskName = $task->getTaskName();

        $this->assertFalse($cli->taskNameIsRegistered($expectedTaskName));

        $cli->setRegisteredTasks(array($expectedClassName => $task));
        $this->assertTrue($cli->taskNameIsRegistered($expectedTaskName, $actualClassName));
        $this->assertEqual($expectedClassName, $actualClassName);
    }

    public function testDoctrineTaskClassesAreNotAlreadyLoaded()
    {
        foreach ($this->doctrineTaskClassName as $taskClassName) {
            $this->assertFalse(class_exists($taskClassName, false));
        }
    }

    //Apologies for this cheap, non-atomic test - this area needs some more work once this round of refactoring's done
    public function testAutomaticallyIncludesAndRegistersDoctrineTasks()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli(array('autoregister_custom_tasks' => false));

        //Make sure those Doctrine core Tasks are loaded
        foreach ($this->doctrineTaskClassName as $className => $taskName) {
            $this->assertTrue(class_exists($className, false));
            $this->assertTrue($cli->taskClassIsRegistered($className));
        }

        //Now, make sure we haven't registered any custom Tasks
        $this->assertFalse($cli->taskClassIsRegistered('Doctrine_Cli_TestCase_EmptyTask'));
    }

    public function testByDefaultAutomaticallyRegistersIncludedCustomTasks()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli();
        $this->assertTrue($cli->taskClassIsRegistered('Doctrine_Cli_TestCase_EmptyTask'));
    }

    public function testRegistertaskclassRegistersTheSpecifiedClass()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli();

        $this->assertFalse($cli->taskClassIsRegistered('Doctrine_Cli_TestCase_TestTask02'));

        require_once($this->getFixturesPath() . '/TestTask02.php');
        $cli->registerTaskClass('Doctrine_Cli_TestCase_TestTask02');
        $this->assertTrue($cli->taskClassIsRegistered('Doctrine_Cli_TestCase_TestTask02'));

        //Nothing should happen if we attempt to register a registered class
        $cli->registerTaskClass('Doctrine_Cli_TestCase_TestTask02');
        $this->assertTrue($cli->taskClassIsRegistered('Doctrine_Cli_TestCase_TestTask02'));
    }

    public function testRegistertaskclassThrowsAnExceptionIfTheSpecifiedClassIsNotLoaded()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli();

        try {
            $cli->registerTaskClass('anything');
        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() == 'The task class "anything" does not exist') {
                $this->pass();
                return;
            }
        }

        $this->fail();
    }

    public function testRegistertaskclassThrowsAnExceptionIfTheSpecifiedClassIsNotATask()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli();

        $thisClassName = get_class($this);

        try {
            $cli->registerTaskClass($thisClassName);
        } catch (DomainException $e) {
            if ($e->getMessage() == "The class \"{$thisClassName}\" is not a Doctrine Task") {
                $this->pass();
                return;
            }
        }

        $this->fail();
    }

    /*
     * Exists only to ensure the method behaves the same as it did before refactoring
     */
    public function testLoadtasksThrowsAnExceptionIfTheSpecifiedDirectoryDoesNotExist()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();

        $directory = $this->getFixturesPath() . '/foo';
    
        try {
            $cli->loadTasks($directory);
        } catch (InvalidArgumentException $e) {
            if ($e->getMessage() == "The directory \"{$directory}\" does not exist") {
                $this->pass();
                return;
            }
        }
    
        $this->fail();
    }

    /*
     * Exists (mostly) to ensure the method behaves the same as it did before refactoring
     */
    public function testLoadtasksLoadsDoctrineStyleTasksFromTheSpecifiedDirectory()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();
    
        $this->assertEqual(array(), $cli->getRegisteredTasks());
    
        $loadedTaskName = $cli->loadTasks($this->getFixturesPath() . '/' . __FUNCTION__);
        $expectedTaskName = array('doctrine-style-task' => 'doctrine-style-task');
        $this->assertEqual($expectedTaskName, $loadedTaskName);

        $registeredTasks = $cli->getRegisteredTasks();

        $this->assertTrue(isset($registeredTasks['Doctrine_Task_DoctrineStyleTask']));

        $this->assertFalse(isset($registeredTasks['Doctrine_Cli_TestCase_InvalidClassNameForATask']));
        $this->assertFalse(isset($registeredTasks['Doctrine_Task_TaskDeclaredInAnIncFile']));
    }

    /*
     * Exists only to ensure the method behaves the same as it did before refactoring
     */
    public function testLoadtasksReturnsAnArrayOfTaskNames()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli();
        $loadedTaskNames = $cli->loadTasks();
        $expectedTaskName = array_combine($this->doctrineTaskClassName, $this->doctrineTaskClassName);
        $this->assertEqual($expectedTaskName, array_intersect_assoc($expectedTaskName, $loadedTaskNames));
    }

    /*
     * Exists only to ensure the method behaves the same as it did before refactoring
     */
    public function testGetloadedtasksReturnsAnArrayOfTaskNames()
    {
        $cli = new Doctrine_Cli_TestCase_EmptyCli();
        $loadedTaskNames = $cli->getLoadedTasks();
        $expectedTaskName = array_combine($this->doctrineTaskClassName, $this->doctrineTaskClassName);
        $this->assertEqual($expectedTaskName, array_intersect_assoc($expectedTaskName, $loadedTaskNames));
    }
    
    /*
     * Exists only to ensure the method behaves the same as it did before refactoring
     */
    public function test_gettaskclassfromargsReturnsTheNameOfTheClassAssociatedWithTheSpecifiedTask()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli02();
        $this->assertEqual('Doctrine_Task_TaskName', $cli->_getTaskClassFromArgs(array('scriptName', 'task-name')));
    }

    public function testRunByDefaultDoesNotThrowExceptions()
    {
        //Hide printed output from the CLI
        ob_start();

        $cli = new Doctrine_Cli_TestCase_NoisyCli();
        $cli->run(array());
        $this->pass();
    
        $cli = new Doctrine_Cli_TestCase_NoisyCli(array('rethrow_exceptions' => false));
        $cli->run(array());
        $this->pass();
    
        $cli = new Doctrine_Cli_TestCase_NoisyCli(array('rethrow_exceptions' => 0));
        $cli->run(array());
        $this->pass();

        ob_end_clean();
    }

    public function testRunThrowsExceptionsIfTheCliWasConstructedWithTheRethrowexceptionsOptionSetToTrue()
    {
        //Hide printed output from the CLI
        ob_start();

        $cli = new Doctrine_Cli_TestCase_NoisyCli(array('rethrow_exceptions' => 1));
    
        try {
            $cli->run(array());
        //The same exception must be re-thrown...
        } catch (Doctrine_Cli_TestCase_Exception $e) {
            //...And it must be formatted
            if (preg_match('/Foo\W+/', $e->getMessage())) {
                $this->pass();
                return;
            }
        }
    
        $this->fail();

        ob_end_clean();
    }

    public function testGettaskinstanceReturnsTheTaskSetWithSettaskinstance()
    {
        $cli = new Doctrine_Cli_TestCase_PassiveCli();
        $task = new Doctrine_Cli_TestCase_EmptyTask();
        $cli->setTaskInstance($task);
        $this->assertIdentical($task, $cli->getTaskInstance());
    }
}

class Doctrine_Cli_TestCase_PassiveCli extends Doctrine_Cli
{
    protected function includeAndRegisterTaskClasses() {}
}

class Doctrine_Cli_TestCase_EmptyCli extends Doctrine_Cli
{
}

class Doctrine_Cli_TestCase_EmptyTask extends Doctrine_Task
{
    public function execute() {}
}

class Doctrine_Cli_TestCase_PassiveCli02 extends Doctrine_Cli_TestCase_PassiveCli
{
    public function _getTaskClassFromArgs(array $args)
    {
        return parent::_getTaskClassFromArgs($args);
    }
}

class Doctrine_Cli_TestCase_Exception extends Exception
{
}

class Doctrine_Cli_TestCase_NoisyCli extends Doctrine_Cli_TestCase_PassiveCli
{
    protected function _run(array $args)
    {
        throw new Doctrine_Cli_TestCase_Exception('Foo');
    }
}

class Doctrine_Cli_TestCase_TestTask01 extends Doctrine_Task
{
    public function execute() {}
}