<?php
class GroupTest extends UnitTestCase
{
    protected $_testCases = array();
    protected $_name;
    protected $_title;
    protected $_onlyRunFailed = false;

    public function __construct($title, $name)
    {
        $this->_title = $title;
        $this->_name =  $name;
        if ( PHP_SAPI != 'cli' && ! defined('STDOUT')) {
            define('STDOUT', '');
        }
        $this->_formatter = new Doctrine_Cli_AnsiColorFormatter();
    }

    public function onlyRunFailed($bool)
    {
        $this->_onlyRunFailed = $bool;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function addTestCase(UnitTestCase $testCase)
    {
        if ($testCase instanceOf GroupTest) {
            $this->_testCases = array_merge($this->_testCases, $testCase->getTestCases());
         } else {
            $this->_testCases[get_class($testCase)] = $testCase;
         }
    }

    public function shouldBeRun($testCase, $filter)
    {
        if ( ! is_array($filter)) {
            return true;
        }
        foreach($filter as $subFilter) {
            $name = strtolower(get_class($testCase));
            $pos = strpos($name, strtolower($subFilter));
            //it can be 0 so we have to use === to see if false
            if ($pos === false) {
                return false;
            }
        }
        return true;
    }
    public function run(DoctrineTest_Reporter $reporter = null, $filter = null)
    {
        set_time_limit(900);

        $this->init();

        $reporter->setTestCase($this);
        $reporter->paintHeader($this->_title);

        $lastRunsFails = $this->getLastRunsFails();

        foreach ($this->_testCases as $k => $testCase) {
            if ($this->_onlyRunFailed && ! isset($lastRunsFails[get_class($testCase)])) {
                continue;
            }

            $reporter->setTestCase($testCase);

            if ( ! $this->shouldBeRun($testCase, $filter)) {
                continue;
            }
            try {
                $testCase->run();
            } catch (Exception $e) {
                $this->_failed += 1;
                $message = 'Unexpected ' . get_class($e) . ' thrown in [' . get_class($testCase) . '] with message [' . $e->getMessage() . '] in ' . $e->getFile() . ' on line ' . $e->getLine() . "\n\nTrace\n-------------\n\n" . $e->getTraceAsString();
                $testCase->addMessage($message);
            }

            $this->_passed += $testCase->getPassCount();
            $this->_failed += $testCase->getFailCount();

            $this->_testCases[$k] = null;

            $reporter->paintMessages();
        }

        $reporter->setTestCase($this);

        $reporter->paintMessages();

        $this->cachePassesAndFails();

        $reporter->paintFooter();

        return $this->_failed ? false : true;
    }

    public function getTestCaseCount()
    {
        return count($this->_testCases);
    }

    public function getTestCases()
    {
        return $this->_testCases;
    }
}