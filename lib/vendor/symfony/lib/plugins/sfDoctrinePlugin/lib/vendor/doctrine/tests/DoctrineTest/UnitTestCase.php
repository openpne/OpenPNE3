<?php
class UnitTestCase
{
    protected $_passed = 0;
    
    protected $_failed = 0;
    
    protected $_messages = array();

    protected static $_passesAndFails = array('passes' => array(), 'fails' => array());

    protected static $_lastRunsPassesAndFails = array('passes' => array(), 'fails' => array());

    public function init()
    {
        $tmpFileName = $this->getPassesAndFailsCachePath();

        if (file_exists($tmpFileName)) {
            $array = unserialize(file_get_contents($tmpFileName));
        } else {
            $array = array();
        }
        if ($array) {
            self::$_lastRunsPassesAndFails = $array;
        }
    }

    public function addMessage($msg)
    {
        $this->_messages[] = $msg;
    }

    public function assertEqual($value, $value2)
    {
        if ($value == $value2) {
            $this->pass();
        } else {
            $seperator = "<br>";
            if (PHP_SAPI === "cli") {
                $seperator = "\n";
            }

            if (is_array($value)) {
                $value = var_export($value, true);
            }

            if (is_array($value2)) {
                $value2 = var_export($value2, true);
            }

            $message = "$seperator Value1: $value $seperator != $seperator Value2: $value2 $seperator";
            $this->_fail($message);
        }
    }

    public function assertIdentical($value, $value2)
    {
        if ($value === $value2) {
            $this->pass();
        } else {
            $this->_fail();
        }
    }

    public function assertNotEqual($value, $value2)
    {
        if ($value != $value2) {
            $this->pass();
        } else {
            $this->_fail();
        }
    }

    public function assertTrue($expr)
    {
        if ($expr) {
            $this->pass();
        } else {
            $this->_fail();
        }
    }

    public function assertFalse($expr)
    {
        if ( ! $expr) {
            $this->pass();
        } else {
            $this->_fail();
        }
    }

    public function assertNull($expr)
    {
        if (is_null($expr)) {
            $this->pass();
        } else {
            $this->fail();
        }
    }

    public function assertNotNull($expr)
    {
        if (is_null($expr)) {
            $this->fail();
        } else {
            $this->pass();
        }
    }

    public function pass() 
    {
        $class = get_class($this);
        if ( ! isset(self::$_passesAndFails['fails'][$class])) {
            self::$_passesAndFails['passes'][$class] = $class;
        }
        $this->_passed++;
    }

    public function fail($message = "")
    {
        $this->_fail($message);    
    }

    public function _fail($message = "")
    {
        $trace = debug_backtrace();
        array_shift($trace);


        foreach ($trace as $stack) {
            if (substr($stack['function'], 0, 4) === 'test') {
                $class = new ReflectionClass($stack['class']);

                if ( ! isset($line)) {
                    $line = $stack['line'];
                }

                $errorMessage = $class->getName() . ' : method ' . $stack['function'] . ' failed on line ' . $line;
                $this->_messages[] =  $errorMessage . " " . $message;
                break;
            }
            $line = $stack['line'];
        }
        $this->_failed++;
        $class = get_class($this);
        if (isset(self::$_passesAndFails['passes'][$class])) {
            unset(self::$_passesAndFails['passes'][$class]);
        }
        self::$_passesAndFails['fails'][$class] = $class;
    }

    public function run(DoctrineTest_Reporter $reporter = null, $filter = null) 
    {
        foreach (get_class_methods($this) as $method) {
            if (substr($method, 0, 4) === 'test') {
                $this->setUp();

                $this->$method();
                
                $this->tearDown();
            }
        }
    }

    public function getMessages() 
    {
        return $this->_messages;
    }

    public function getFailCount()
    {
        return $this->_failed;
    }

    public function getPassCount()
    {
        return $this->_passed;
    }

    public function getPassesAndFailsCachePath()
    {
        $dir = dirname(__FILE__) . '/doctrine_tests';
        if ( ! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = $dir . '/' . md5(serialize(array_keys($this->_testCases)));
        return $path;
    }

    public function cachePassesAndFails()
    {
        $tmpFileName = $this->getPassesAndFailsCachePath();
        file_put_contents($tmpFileName, serialize(self::$_passesAndFails));
    }

    public function getPassesAndFails()
    {
        return self::$_passesAndFails;
    }

    public function getLastRunsPassesAndFails()
    {
        return self::$_lastRunsPassesAndFails;
    }

    public function getLastRunsFails()
    {
        return isset(self::$_lastRunsPassesAndFails['fails']) ? self::$_lastRunsPassesAndFails['fails'] : array();
    }

    public function getLastRunsPass()
    {
        return isset(self::$_lastRunsPassesAndFails['passes']) ? self::$_lastRunsPassesAndFails['passes'] : array();
    }

    public function getNewFails()
    {
        $newFails = array();
        $fails = self::$_passesAndFails['fails'];
        foreach ($fails as $fail) {
            // If it passed before then it is a new fail
            if (isset(self::$_lastRunsPassesAndFails['passes'][$fail])) {
                $newFails[$fail] = $fail;
            }
        }
        return $newFails;;
    }

    public function getFixedFails()
    {
        $fixed = array();
        $fails = self::$_lastRunsPassesAndFails['fails'];
        foreach ($fails as $fail) {
            // If the fail passes this time then it is fixed
            if (isset(self::$_passesAndFails['passes'][$fail])) {
                $fixed[$fail] = $fail;
            }
        }
        return $fixed;;
    }

    public function getNumNewFails()
    {
        return count($this->getNewFails());
    }

    public function getNumFixedFails()
    {
        return count($this->getFixedFails());
    }
}