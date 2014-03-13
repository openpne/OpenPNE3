<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007, Clay Loveless                               |
// | All rights reserved.                                                 |
// +----------------------------------------------------------------------+
// | This LICENSE is in the BSD license style.                            |
// | http://www.opensource.org/licenses/bsd-license.php                   |
// |                                                                      |
// | Redistribution and use in source and binary forms, with or without   |
// | modification, are permitted provided that the following conditions   |
// | are met:                                                             |
// |                                                                      |
// |  * Redistributions of source code must retain the above copyright    |
// |    notice, this list of conditions and the following disclaimer.     |
// |                                                                      |
// |  * Redistributions in binary form must reproduce the above           |
// |    copyright notice, this list of conditions and the following       |
// |    disclaimer in the documentation and/or other materials provided   |
// |    with the distribution.                                            |
// |                                                                      |
// |  * Neither the name of Clay Loveless nor the names of contributors   |
// |    may be used to endorse or promote products derived from this      |
// |    software without specific prior written permission.               |
// |                                                                      |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS  |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT    |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS    |
// | FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE      |
// | COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,  |
// | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, |
// | BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;     |
// | LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER     |
// | CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT   |
// | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN    |
// | ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE      |
// | POSSIBILITY OF SUCH DAMAGE.                                          |
// +----------------------------------------------------------------------+
// | Author: Clay Loveless <clay@killersoft.com>                          |
// +----------------------------------------------------------------------+
//
// $Id: Commit.php 286753 2009-08-03 19:37:03Z mrook $
//

/**
 * @package     VersionControl_SVN
 * @category    VersionControl
 * @author      Clay Loveless <clay@killersoft.com>
 */

/**
 * Subversion Commit command manager class
 *
 * Send changes from a working copy to the repository.
 *
 * Commits must include a commit message option (either with 'm', 'message', 'file' or 'F'
 * keys), but the message itself may be blank.
 *
 * $switches is an array containing one or more command line options
 * defined by the following associative keys:
 *
 * <code>
 *
 * $switches = array(
 *  'm [message]'   =>  'Specified commit message',
 *                      // either 'm' or 'message' may be used
 *  'F [file]'      =>  'Read commit message data from specified file',
 *                      // either 'F' or 'file' may be used
 *  'q [quiet]'     =>  true|false,
 *                      // prints as little as possible
 *  'N'             =>  true|false,
 *                      // operate on single directory only
 *  'non-recursive' =>  true|false,
 *                      // operate on single directory only
 *  'targets'       =>  'ARG',
 *                      // pass contents of file ARG as additional args
 *  'force-log'     =>  true|false,
 *                      // force validity of log message source
 *  'username'      =>  'Subversion repository login',
 *  'password'      =>  'Subversion repository password',
 *  'no-auth-cache' =>  true|false,
 *                      // Do not cache authentication tokens
 *  'encoding'      =>  'ARG',
 *                      // treat value as being in charset encoding ARG
 *  'config-dir'    =>  'Path to a Subversion configuration directory'
 * );
 *
 * </code>
 *
 * If a path is not used in the $args array, the default path of '.' will
 * be assumed.
 *
 * Note: Subversion does not offer an XML output option for this subcommand
 *
 * The non-interactive option available on the command-line 
 * svn client may also be set (true|false), but it is set to true by default.
 *
 * The editor-cmd option available on the command-line svn client is not available
 * since this class does not operate as an interactive shell session.
 *
 * Usage example:
 * <code>
 * <?php
 * require_once 'VersionControl/SVN.php';
 *
 * // Setup error handling -- always a good idea!
 * $svnstack = &PEAR_ErrorStack::singleton('VersionControl_SVN');
 *
 * // Set up runtime options. Will be passed to all 
 * // subclasses.
 * $options = array('fetchmode' => VERSIONCONTROL_SVN_FETCHMODE_RAW);
 *
 * // Pass array of subcommands we need to factory
 * $svn = VersionControl_SVN::factory(array('checkout'), $options);
 *
 * // Define any switches and aguments we may need
 * $switches = array('m' => 'Bug #1234 fixed!', 'username' => 'user', 'password' => 'pass');
 * $args = array('/path/to/working_copy');
 *
 * // Run command
 * if ($output = $svn->commit->run($args, $switches)) {
 *     print_r($output);
 * } else {
 *     if (count($errs = $svnstack->getErrors())) { 
 *         foreach ($errs as $err) {
 *             echo '<br />'.$err['message']."<br />\n";
 *             echo "Command used: " . $err['params']['cmd'];
 *         }
 *     }
 * }
 * ?>
 * </code>
 *
 * @package  VersionControl_SVN
 * @version  @version@
 * @category SCM
 * @author   Clay Loveless <clay@killersoft.com>
 */
class VersionControl_SVN_Commit extends VersionControl_SVN
{
    /**
     * Valid switches for svn commit
     *
     * @var     array
     * @access  public
     */
    var $valid_switches = array('m',
                                'message',
                                'q',
                                'quiet',
                                'N',
                                'non-recursive',
                                'non_recursive',
                                'username',
                                'password',
                                'no-auth-cache',
                                'no_auth_cache',
                                'non-interactive',
                                'non_interactive',
                                'config-dir',
                                'config_dir',
                                'targets',
                                'file',
                                'F',
                                'encoding',
                                'force-log',
                                'force_log'
                                );

    /**
     * Command-line arguments that should be passed 
     * <b>outside</b> of those specified in {@link switches}.
     *
     * @var     array
     * @access  public
     */
    var $args = array();
    
    /**
     * Minimum number of args required by this subcommand.
     * See {@link http://svnbook.red-bean.com/svnbook/ Version Control with Subversion}, 
     * Subversion Complete Reference for details on arguments for this subcommand.
     * @var     int
     * @access  public
     */
    var $min_args = 0;
    
    /**
     * Switches required by this subcommand.
     * See {@link http://svnbook.red-bean.com/svnbook/ Version Control with Subversion}, 
     * Subversion Complete Reference for details on arguments for this subcommand.
     * @var     array
     * @access  public
     */
    var $required_switches = array('m|message|F|file');
    
    /**
     * Use exec or passthru to get results from command.
     * @var     bool
     * @access  public
     */
    var $passthru = false;
    
    /**
     * Prepare the svn subcommand switches.
     *
     * Defaults to non-interactive mode, and will auto-set the 
     * --xml switch (if available) if $fetchmode is set to VERSIONCONTROL_SVN_FETCHMODE_XML,
     * VERSIONCONTROL_SVN_FETCHMODE_ASSOC or VERSIONCONTROL_SVN_FETCHMODE_OBJECT
     *
     * @param   void
     * @return  int    true on success, false on failure. Check PEAR_ErrorStack
     *                 for error details, if any.
     */
    function prepare()
    {
        $meets_requirements = $this->checkCommandRequirements();
        if (!$meets_requirements) {
            return false;
        }
        
        $valid_switches     = $this->valid_switches;
        $switches           = $this->switches;
        $args               = $this->args;
        $fetchmode          = $this->fetchmode;
        $invalid_switches   = array();
        $_switches          = '';
        
        foreach ($switches as $switch => $val) {
            if (in_array($switch, $valid_switches)) {
                $switch = str_replace('_', '-', $switch);
                switch ($switch) {
                    case 'username':
                    case 'password':
                    case 'encoding':
                    case 'config-dir':
                    case 'targets':
                        $_switches .= "--$switch $val ";
                        break;
                    case 'F':
                    case 'm':
                        $_switches .= "-$switch \"$val\" ";
                        break;
                    case 'file':
                    case 'message':
                        $_switches .= "--$switch \"$val\" ";
                        break;
                    case 'quiet':
                    case 'no-auth-cache':
                    case 'force-log':
                    case 'non-recursive':
                        if ($val === true) {
                            $_switches .= "--$switch ";
                        }
                        break;
                    case 'q':
                    case 'N':
                        if ($val === true) {
                            $_switches .= "-$switch ";
                        }
                        break;
                    default:
                        // that's all, folks!
                        break;
                }
            } else {
                $invalid_switches[] = $switch;
            }
        }

        // We don't want interactive mode
        if (strpos($_switches, 'non-interactive') === false) {
            $_switches .= '--non-interactive ';
        }

        $_switches = trim($_switches);
        $this->_switches = $_switches;

        $cmd = "$this->svn_path $this->_svn_cmd $_switches";
        if (!empty($args)) {
            $cmd .= ' '. join(' ', $args);
        }
        
        $this->_prepped_cmd = $cmd;
        $this->prepared = true;

        $invalid = count($invalid_switches);
        if ($invalid > 0) {
            $params['was'] = 'was';
            $params['is_invalid_switch'] = 'is an invalid switch';
            if ($invalid > 1) {
                $params['was'] = 'were';
                $params['is_invalid_switch'] = 'are invalid switches';
            }
            $params['list'] = $invalid_switches;
            $params['switches'] = $switches;
            $params['_svn_cmd'] = ucfirst($this->_svn_cmd);
            $this->_stack->push(VERSIONCONTROL_SVN_NOTICE_INVALID_SWITCH, 'notice', $params);
        }
        return true;
    }
    
    // }}}
    // {{{ parseOutput()
    
    /**
     * Handles output parsing of standard and verbose output of command.
     *
     * @param   array   $out    Array of output captured by exec command in {@link run}.
     * @return  mixed   Returns output requested by fetchmode (if available), or raw output
     *                  if desired fetchmode is not available.
     * @access  public
     */
    function parseOutput($out)
    {
        $fetchmode = $this->fetchmode;
        switch($fetchmode) {
            case VERSIONCONTROL_SVN_FETCHMODE_RAW:
                return join("\n", $out);
                break;
            case VERSIONCONTROL_SVN_FETCHMODE_ASSOC:
                // Temporary, see parseOutputArray below
                return join("\n", $out);
                break;
            case VERSIONCONTROL_SVN_FETCHMODE_OBJECT:
                // Temporary, will return object-ified array from
                // parseOutputArray
                return join("\n", $out);
                break;
            case VERSIONCONTROL_SVN_FETCHMODE_XML:
                // Temporary, will eventually build an XML string
                // with XML_Util or XML_Tree
                return join("\n", $out);
                break;
            default:
                // What you get with VERSIONCONTROL_SVN_FETCHMODE_DEFAULT
                return join("\n", $out);
                break;
        }
    }
    
    /**
     * Helper method for parseOutput that parses output into an associative array
     *
     * @todo Finish this method! : )
     */
    function parseOutputArray($out)
    {
        $parsed = array();
    }
}

/// }}}
?>