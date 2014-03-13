<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * +----------------------------------------------------------------------+
 * | PHP version 5                                                        |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2004-2007, Clay Loveless                               |
 * | All rights reserved.                                                 |
 * +----------------------------------------------------------------------+
 * | This LICENSE is in the BSD license style.                            |
 * | http://www.opensource.org/licenses/bsd-license.php                   |
 * |                                                                      |
 * | Redistribution and use in source and binary forms, with or without   |
 * | modification, are permitted provided that the following conditions   |
 * | are met:                                                             |
 * |                                                                      |
 * |  * Redistributions of source code must retain the above copyright    |
 * |    notice, this list of conditions and the following disclaimer.     |
 * |                                                                      |
 * |  * Redistributions in binary form must reproduce the above           |
 * |    copyright notice, this list of conditions and the following       |
 * |    disclaimer in the documentation and/or other materials provided   |
 * |    with the distribution.                                            |
 * |                                                                      |
 * |  * Neither the name of Clay Loveless nor the names of contributors   |
 * |    may be used to endorse or promote products derived from this      |
 * |    software without specific prior written permission.               |
 * |                                                                      |
 * | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
 * | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
 * | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS    |
 * | FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE      |
 * | COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,  |
 * | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, |
 * | BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;     |
 * | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER     |
 * | CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT   |
 * | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN    |
 * | ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE      |
 * | POSSIBILITY OF SUCH DAMAGE.                                          |
 * +----------------------------------------------------------------------+
 *
 * @category  VersionControl
 * @package   VersionControl_SVN
 * @author    Clay Loveless <clay@killersoft.com>
 * @author    Michiel Rook <mrook@php.net>
 * @copyright 2004-2007 Clay Loveless
 * @license   http://www.killersoft.com/LICENSE.txt BSD License
 * @version   SVN: $Id$
 * @link      http://pear.php.net/package/VersionControl_SVN
 */

// {{{ Error Management
require_once 'PEAR/ErrorStack.php';

// error & notice constants
define('VERSIONCONTROL_SVN_ERROR', -1);
define('VERSIONCONTROL_SVN_ERROR_NO_VERSION', -2);
define('VERSIONCONTROL_SVN_ERROR_NO_REVISION', -3);
define('VERSIONCONTROL_SVN_ERROR_UNKNOWN_CMD', -4);
define('VERSIONCONTROL_SVN_ERROR_NOT_IMPLEMENTED', -5);
define('VERSIONCONTROL_SVN_ERROR_NO_SWITCHES', -6);
define('VERSIONCONTROL_SVN_ERROR_UNDEFINED', -7);
define('VERSIONCONTROL_SVN_ERROR_REQUIRED_SWITCH_MISSING', -8);
define('VERSIONCONTROL_SVN_ERROR_MIN_ARGS', -9);
define('VERSIONCONTROL_SVN_ERROR_EXEC', -10);
define('VERSIONCONTROL_SVN_NOTICE', -999);
define('VERSIONCONTROL_SVN_NOTICE_INVALID_SWITCH', -901);
define('VERSIONCONTROL_SVN_NOTICE_INVALID_OPTION', -902);

// }}}
// {{{ fetch modes

/**
 * Note on the fetch modes -- as the project matures, more of these modes
 * will be implemented. At the time of initial release only the 
 * Log and List commands implement anything other than basic
 * RAW output.
 */

/**
 * This is a special constant that tells VersionControl_SVN the user hasn't specified
 * any particular get mode, so the default should be used.
 */
define('VERSIONCONTROL_SVN_FETCHMODE_DEFAULT', 0);

/**
 * Responses returned in associative array format
 */
define('VERSIONCONTROL_SVN_FETCHMODE_ASSOC', 1);

/**
 * Responses returned as object properties
 */
define('VERSIONCONTROL_SVN_FETCHMODE_OBJECT', 2);

/**
 * Responses returned as raw XML (as passed-thru from svn --xml command responses)
 */
define('VERSIONCONTROL_SVN_FETCHMODE_XML', 3);

/**
 * Responses returned as string - unmodified from command-line output
 */
define('VERSIONCONTROL_SVN_FETCHMODE_RAW', 4);

/**
 * Responses returned as raw output, but all available output parsing methods
 * are performed and stored in the {@link output} property.
 */
define('VERSIONCONTROL_SVN_FETCHMODE_ALL', 5);

/**
 * Responses returned as numbered array
 */
define('VERSIONCONTROL_SVN_FETCHMODE_ARRAY', 6);

// }}}

/**
 * Simple OO interface for Subversion 
 *
 * @tutorial  VersionControl_SVN.pkg
 * @category  VersionControl
 * @package   VersionControl_SVN
 * @author    Clay Loveless <clay@killersoft.com>
 * @author    Michiel Rook <mrook@php.net>
 * @copyright 2004-2007 Clay Loveless
 * @license   http://www.killersoft.com/LICENSE.txt BSD License
 * @version   @version@
 * @link      http://pear.php.net/package/VersionControl_SVN
 * @static
 */
class VersionControl_SVN
{

    // {{{ Public Properties
    
    /**
     * Reference array of subcommand shortcuts. Provided for convenience for 
     * those who prefer the shortcuts they're used to using with the svn
     * command-line tools.
     *
     * You may specify your own shortcuts by passing them in to the factory.
     * For example:
     *
     * <code>
     * <?php
     * require_once 'VersionControl/SVN.php';
     *
     * $options['shortcuts'] = array('boot' => 'Delete', 'checkin' => 'Commit');
     *
     * $svn = VersionControl_SVN::factory(array('boot', 'checkin'), $options);
     *
     * $switches = array('username' => 'user', 'password' => 'pass', 'force' => true);
     * $args = array('svn://svn.example.com/repos/TestProject/file_to_delete.txt');
     *
     * $svn->boot->run($switches, $args);
     *
     * ?>
     * </code>
     *
     * @var     array
     * @access  public
     */
    var $shortcuts = array(

            'praise'    => 'Blame',
            'annotate'  => 'Blame',
            'ann'       => 'Blame',
            'co'        => 'Checkout',
            'ci'        => 'Commit',
            'cp'        => 'Copy',
            'del'       => 'Delete',
            'remove'    => 'Delete',
            'rm'        => 'Delete',
            'di'        => 'Diff',
            'ls'        => 'List',
            'mv'        => 'Move',
            'rename'    => 'Move',
            'ren'       => 'Move',
            'pdel'      => 'Propdel',
            'pd'        => 'Propdel',
            'pget'      => 'Propget',
            'pg'        => 'Propget',
            'plist'     => 'Proplist',
            'pl'        => 'Proplist',
            'pset'      => 'Propset',
            'ps'        => 'Propset',
            'stat'      => 'Status',
            'st'        => 'Status',
            'sw'        => 'Switch',
            'up'        => 'Update'
        );
    
    /**
     * Indicates whether commands passed to the {@link http://www.php.net/exec exec()} function in 
     * the {@link run} method should be passed through
     * {@link http://www.php.net/escapeshellcmd escapeshellcmd()}.
     * NOTE: this variable is ignored on Windows machines!
     *
     * @var     bool
     * @access  public
     */
    var $use_escapeshellcmd = true;

    /**
     * Location of the svn client binary installed as part of Subversion
     *
     * @var     string  $svn_path
     * @access  public
     */
    var $svn_path = '/usr/local/bin/svn';

    /**
     * String to prepend to command string. Helpful for setting exec() 
     * environment variables, such as: 
     *    export LANG=en_US.utf8 &&
     * ... to support non-ASCII file and directory names.
     * 
     * @var     string $prepend_cmd
     * @access  public
     */
    var $prepend_cmd = '';

    /**
     * Array of switches to use in building svn command
     *
     * @var     array
     * @access  public
     */
    var $switches = array();

    /**
     * Runtime options being used. 
     *
     * @var     array
     * @access  public
     */
    var $options = array();
    
    /**
     * Preferred fetchmode. Note that not all subcommands have output available for 
     * each preferred fetchmode. The default cascade is:
     *
     * VERSIONCONTROL_SVN_FETCHMODE_ASSOC
     *  VERSIONCONTROL_SVN_FETCHMODE_RAW
     *
     * If the specified fetchmode isn't available, raw output will be returned.
     * 
     * @var     int
     * @access  public
     */
    var $fetchmode = VERSIONCONTROL_SVN_FETCHMODE_ASSOC;

    /**
     * XML::Parser class to use for parsing XML output
     * 
     * @var     string
     * @access  public
     */
    var $svn_cmd_parser;

    // }}}
    // {{{ Private Properties

    /**
     * SVN subcommand to run.
     * 
     * @var     string
     * @access  private
     */
    var $_svn_cmd = '';

    /**
     * Keep track of whether options are prepared or not.
     *
     * @var     bool
     * @access  private
     */
    var $_prepared = false;

    /**
     * Fully prepared command string.
     * 
     * @var     string
     * @access  private
     */
    var $_prepped_cmd = '';

    /**
     * Keep track of whether XML output is available for a command
     *
     * @var     bool
     * @access  private
     */
    var $_xml_avail = false;

    /**
     * Error stack.
     *
     * @var     object
     * @access  private
     */
    var $_stack;
    
    /**
     * Assembled switches for command line execution
     * 
     * @var     object
     * @access  private
     */
    var $_switches = '';
    
    // }}}
    // {{{ errorMessages()
    
    /**
     * Set up VersionControl_SVN error message templates for PEAR_ErrorStack.
     *
     * @return  array
     * @access  public
     */
    function declareErrorMessages()
    {
        $messages = array(
            VERSIONCONTROL_SVN_ERROR => '%errstr%',
            VERSIONCONTROL_SVN_ERROR_EXEC => '%errstr% (cmd: %cmd%)',
            VERSIONCONTROL_SVN_ERROR_NO_VERSION => 
                'undefined 2',
            VERSIONCONTROL_SVN_ERROR_NO_REVISION => 
                'undefined 3',
            VERSIONCONTROL_SVN_ERROR_UNKNOWN_CMD => 
                '\'%command%\' is not a known VersionControl_SVN command.',
            VERSIONCONTROL_SVN_ERROR_NOT_IMPLEMENTED => 
                '\'%method%\' is not implemented in the %class% class.',
            VERSIONCONTROL_SVN_ERROR_NO_SWITCHES => 
                'undefined 6',
            VERSIONCONTROL_SVN_ERROR_REQUIRED_SWITCH_MISSING => 
                'svn %_svn_cmd% requires the following %switchstr%: %missing%',
            VERSIONCONTROL_SVN_ERROR_MIN_ARGS => 
                'svn %_svn_cmd% requires at least %min_args% %argstr%',
            VERSIONCONTROL_SVN_NOTICE => '%notice%',
            VERSIONCONTROL_SVN_NOTICE_INVALID_SWITCH => 
                '\'%list%\' %is_invalid_switch% for VersionControl_SVN_%_svn_cmd% and %was% ignored. ' .
                    'Please refer to the documentation.',
            VERSIONCONTROL_SVN_NOTICE_INVALID_OPTION =>
                '\'%option%\' is not a valid option, and was ignored.'
        );
        
        return $messages;
    }
    
    // {{{ factory()
    
    /**
     * Create a new VersionControl_SVN command object.
     *
     * $options is an array containing multiple options
     * defined by the following associative keys:
     *
     * <code>
     *
     * array(
     *  'url'           => 'Subversion repository URL',
     *  'username'      => 'Subversion repository login',
     *  'password'      => 'Subversion repository password',
     *  'config_dir'    => 'Path to a Subversion configuration directory',
     *                     // [DEFAULT: null]
     *  'dry_run'       => true/false, 
     *                     // [DEFAULT: false]
     *  'encoding'      => 'Language encoding to use for commit messages', 
     *                     // [DEFAULT: null]
     *  'svn_path'      => 'Path to the svn client binary installed as part of Subversion',
     *                     // [DEFAULT: /usr/local/bin/svn]
     * )
     *
     * </code>
     *
     * Example 1.
     * <code>
     * <?php
     * require_once 'VersionControl/SVN.php';
     *
     * $options = array(
     *      'url'        => 'https://www.example.com/repos',
     *      'path'       => 'your_project',
     *      'username'   => 'your_login',
     *      'password'   => 'your_password',
     * );
     * 
     * // Run a log command
     * $svn = VersionControl_SVN::factory('log', $options);
     *
     * print_r($svn->run());
     * ?>
     * </code>
     *
     * @param string $command The Subversion command
     * @param array  $options An associative array of option names and
     *                        their values
     *
     * @return  mixed   a newly created VersionControl_SVN command object, or PEAR_ErrorStack
     *                  constant on error
     *
     * @access  public
     */
    function &factory($command, $options = array())
    {
        $stack = &PEAR_ErrorStack::singleton('VersionControl_SVN');
        $stack->setErrorMessageTemplate(VersionControl_SVN::declareErrorMessages());
        if (is_string($command) && strtoupper($command) == '__ALL__') {
            unset($command);
            $command = array();
            $command = VersionControl_SVN::fetchCommands();
        }
        if (is_array($command)) {
            $objects = new stdClass;
            foreach ($command as $cmd) {
                $obj = VersionControl_SVN::init($cmd, $options);
                $objects->$cmd = $obj;
            }
            return $objects;
        } else {
            $obj = VersionControl_SVN::init($command, $options);
            return $obj;
        }
    }
    
    // }}}
    // {{{ init()
    
    /**
     * Initialize an object wrapper for a Subversion subcommand.
     *
     * @param string $command The Subversion command
     * @param array  $options An associative array of option names and
     *                        their values
     *
     * @return  mixed   object on success, false on failure
     * @access public
     */
    function init($command, $options)
    {
        // Check for shortcuts for commands
        $shortcuts = VersionControl_SVN::fetchShortcuts();
        if (isset($options['shortcuts']) && is_array($options['shortcuts'])) {
            foreach ($options['shortcuts'] as $key => $val) {
                $shortcuts[strtolower($key)] = $val;       
            }
        }
        $cmd = isset($shortcuts[strtolower($command)]) ? $shortcuts[strtolower($command)] : $command;
        $lowercmd   = strtolower($cmd);
        $cmd        = ucfirst($lowercmd);
        $class      = 'VersionControl_SVN_'.$cmd;
        if (include_once realpath(dirname(__FILE__)) . "/SVN/{$cmd}.php") {
            if (class_exists($class)) {
                $obj =& new $class;
                $obj->options   = $options;
                $obj->_svn_cmd  = $lowercmd;
                $obj->_stack    = &PEAR_ErrorStack::singleton('VersionControl_SVN');
                $obj->_stack->setErrorMessageTemplate(VersionControl_SVN::declareErrorMessages());
                $obj->setOptions($options);
                return $obj;
            }
        }
        PEAR_ErrorStack::staticPush('VersionControl_SVN', VERSIONCONTROL_SVN_ERROR_UNKNOWN_CMD, 'error', 
            array('command' => $command, 'options' => $options));
        return false;
    }
    
    // }}}
    // {{{ fetchCommands()
    
    /**
     * Scan through the SVN directory looking for subclasses.
     *
     * @return  mixed    array on success, false on failure
     * @access  public
     */
    function fetchCommands()
    {
        $commands = array();
        $dir = realpath(dirname(__FILE__)) . '/SVN';
        $dp = @opendir($dir);
        if (empty($dp)) {
            PEAR_ErrorStack::staticPush('VersionControl_SVN', VERSIONCONTROL_SVN_ERROR, 'error', 
                array('errstr' => "fetchCommands: opendir($dir) failed"));
            return false;
        }
        while ($entry = readdir($dp)) {
            if ($entry{0} == '.' || substr($entry, -4) != '.php') {
                continue;
            }
            $commands[] = substr($entry, 0, -4);
        }
        closedir($dp);
        return $commands;
    }
    
    // }}}
    // {{{ fetchShortcuts()
    
    /**
     * Return the array of pre-defined shortcuts (also known as Alternate Names)
     * for Subversion commands.
     *
     * @return  array
     * @access public
     */
    function fetchShortcuts()
    {
        $vars = get_class_vars('VersionControl_SVN');
        return $vars['shortcuts'];
    }
    
    // }}}
    // {{{ setOptions()
    
    /**
     * Allow for overriding of previously declared options.     
     *
     * @param array $options An associative array of option names and
     *                       their values
     *
     * @return boolean
     */
    function setOptions($options = array())
    {
        $opts = array_filter(array_keys(get_class_vars('VersionControl_SVN')), array($this, '_filterOpts'));
        foreach ($options as $option => $value) {
            if (in_array($option, $opts)) {
                if ($option == 'shortcuts') {
                    $this->shortcuts = array_merge($this->shortcuts, $value);
                } else {
                    $this->$option = $value;
                }
            } else {
                $this->_stack->push(VERSIONCONTROL_SVN_NOTICE_INVALID_OPTION, 'notice', 
                    array('option' => $option));
            }
        }
        return true;
    }
    
    // }}}
    // {{{ prepare()
    
    /**
     * Prepare the command switches.
     *
     * This function should be overloaded by the command class.
     *
     * @return boolean
     */
    function prepare()
    {
        $this->_stack->push(VERSIONCONTROL_SVN_ERROR_NOT_IMPLEMENTED, 'error', 
            array('options' => $this->options, 'method' => 'prepare()', 'class' => get_class($this)));
        return false;
    }
    
    // }}}
    // {{{ checkCommandRequirements()
    
    /**
     * Standardized validation of requirements for a command class.
     *
     * @return mixed   true if all requirements are met, false if 
     *                  requirements are not met. Details of failures
     *                  are pushed into the PEAR_ErrorStack for VersionControl_SVN
     * @access  public
     */
    function checkCommandRequirements()
    {
        // Set up error push parameters to avoid any notices about undefined indexes
        $params['options']  = $this->options;
        $params['switches'] = $this->switches;
        $params['args']     = $this->args;
        $params['_svn_cmd'] = $this->_svn_cmd;
        $params['cmd']      = '';
        
        // Check for minimum arguments
        if (sizeof($this->args) < $this->min_args) {
            $params['argstr'] = $this->min_args > 1 ? 'arguments' : 'argument';
            $params['min_args'] = $this->min_args;
            $this->_stack->push(VERSIONCONTROL_SVN_ERROR_MIN_ARGS, 'error', $params);
            return false;
        }
        
        // Check for presence of required switches
        if (sizeof($this->required_switches) > 0) {
            $missing    = array();
            $switches   = $this->switches;
            $reqsw      = $this->required_switches;
            foreach ($reqsw as $req) {
                $found = false;
                $good_switches = explode('|', $req);
                foreach ($good_switches as $gsw) {
                    if (isset($switches[$gsw])) {
                        $found = true;
                    }
                }
                if (!$found) {
                    $missing[] = '('.$req.')';
                }
            }
            $num_missing = count($missing);
            if ($num_missing > 0) {
                $params['switchstr'] = $num_missing > 1 ? 'switches' : 'switch';
                $params['missing'] = $missing;
                $this->_stack->push(VERSIONCONTROL_SVN_ERROR_REQUIRED_SWITCH_MISSING, 'error', $params);
                return false;
            }
        }
        return true;
    }
    
    // }}}
    // {{{ run()
    
    /**
     * Run the command with the defined switches.
     *
     * @param array $args     Arguments to pass to Subversion
     * @param array $switches Switches to pass to Subversion
     *
     * @return  mixed   $fetchmode specified output on success,
     *                  or false on failure.
     * @access  public
     */
    function run($args = array(), $switches = array())
    {
        if (sizeof($switches) > 0) {
            $this->switches = $switches;
        }
        if (sizeof($args) > 0) {
            $this->args = $args;
        }
        
        // Always prepare, allows for obj re-use. (Request #5021)
        $this->prepare();
        
        $out        = array();
        $ret_var    = null;
        
        $cmd = $this->_prepped_cmd;

        // On Windows, don't use escapeshellcmd, and double-quote $cmd so it's
        // executed as 'cmd /c ""C:\Program Files\SVN\bin\svn.exe" info "C:\Program Files\dev\trunk""
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            $cmd = '"' . $cmd . '"';

            if (!$this->passthru) {
                exec("cmd /C $cmd 2>&1", $out, $ret_var);
            } else {
                passthru("cmd /C $cmd 2>&1", $ret_var);
            }
        }
        else
        {
            if ($this->use_escapeshellcmd) {
                $cmd = escapeshellcmd($cmd);
            }

            if (!$this->passthru) {
                exec("{$this->prepend_cmd}$cmd 2>&1", $out, $ret_var);
            } else {
                passthru("{$this->prepend_cmd}$cmd 2>&1", $ret_var);
            }
        }

        if ($ret_var > 0) {
            $params['options']  = $this->options;
            $params['switches'] = $this->switches;
            $params['args']     = $this->args;
            $params['cmd']      = $cmd;
            foreach ($out as $line) {
                $params['errstr'] = $line;
                $this->_stack->push(VERSIONCONTROL_SVN_ERROR_EXEC, 'error', $params);
            }
            return false;
        }
        return $this->parseOutput($out);
    }
    
    // }}}
    // {{{ parseOutput()
    
    /**
     * Handle output parsing chores.
     *
     * This bare-bones function should be overridden by the command class.
     *
     * @param array $out Array of output captured by exec command in {@link run}
     *
     * @return  mixed   Returns output requested by fetchmode (if available), or 
     *                  raw output if desired fetchmode is not available.
     * @access  public
     */
    function parseOutput($out)
    {
        return join("\n", $out);
    }
    
    // }}}
    // {{{ apiVersion()
    
    /**
     * Return the VersionControl_SVN API version
     *
     * @return  string  the VersionControl_SVN API version number
     *
     * @access  public
     */
    function apiVersion()
    {
        return '@version@';
    }
    
    // }}}
    // {{{ _filterOpts
    
    /**
     * Callback function for array_filter. Keeps _private options
     * out of settable options.
     *
     * @param string $var Passed value
     *
     * @return boolean
     */
    function _filterOpts($var)
    {
        $ret = ($var{0} == '_') ? false : true;
        return $ret;
    }
    // }}}
}

// }}}
?>