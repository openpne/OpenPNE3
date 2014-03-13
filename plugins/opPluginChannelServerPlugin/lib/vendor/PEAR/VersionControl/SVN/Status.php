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
// $Id: Status.php 286753 2009-08-03 19:37:03Z mrook $
//

/**
 * @package     VersionControl_SVN
 * @category    VersionControl
 * @author      Clay Loveless <clay@killersoft.com>
 */

/**
 * Subversion Status command manager class
 *
 * Print the status of working copy files and directories.
 *
 * There are many possible output values from this command. The following
 * is from 'svn help status':
 *
 *  With no args, print only locally modified items (no network access).
 *  With -u, add working revision and server out-of-date information.
 *  With -v, print full revision information on every item.
 *
 *  The first five columns in the output are each one character wide:
 *    First column: Says if item was added, deleted, or otherwise changed
 *      ' ' no modifications
 *      'A' Added
 *      'C' Conflicted
 *      'D' Deleted
 *      'G' Merged
 *      'I' Ignored
 *      'M' Modified
 *      'R' Replaced
 *      'X' item is unversioned, but is used by an externals definition
 *      '?' item is not under version control
 *      '!' item is missing (removed by non-svn command) or incomplete
 *      '~' versioned item obstructed by some item of a different kind
 *    Second column: Modifications of a file's or directory's properties
 *      ' ' no modifications
 *      'C' Conflicted
 *      'M' Modified
 *    Third column: Whether the working copy directory is locked
 *      ' ' not locked
 *      'L' locked
 *    Fourth column: Scheduled commit will contain addition-with-history
 *      ' ' no history scheduled with commit
 *      '+' history scheduled with commit
 *    Fifth column: Whether the item is switched relative to its parent
 *      ' ' normal
 *      'S' switched
 *
 *  The out-of-date information appears in the eighth column (with -u):
 *      '*' a newer revision exists on the server
 *      ' ' the working copy is up to date
 *
 *  Remaining fields are variable width and delimited by spaces:
 *    The working revision (with -u or -v)
 *    The last committed revision and last committed author (with -v)
 *    The working copy path is always the final field, so it can
 *      include spaces.
 *
 *  Example output:
 *    svn status wc
 *     M     wc/bar.c
 *    A  +   wc/qax.c
 *
 *    svn status -u wc
 *     M           965    wc/bar.c
 *           *     965    wc/foo.c
 *    A  +         965    wc/qax.c
 *    Head revision:   981
 *
 *    svn status --show-updates --verbose wc
 *     M           965       938 kfogel       wc/bar.c
 *           *     965       922 sussman      wc/foo.c
 *    A  +         965       687 joe          wc/qax.c
 *                 965       687 joe          wc/zig.c
 *    Head revision:   981
 *
 *
 * $switches is an array containing one or more command line options
 * defined by the following associative keys:
 *
 * <code>
 *
 * $switches = array(
 *  'u'             =>  true|false,
 *                      // display update information
 *  'show-updates'  =>  true|false,
 *                      // display update information
 *  'v [verbose]'   =>  true|false,
 *                      // prints extra information
 *  'N'             =>  true|false,
 *                      // operate on a single directory only
 *  'non-recursive' =>  true|false,
 *                      // operate on a single directory only
 *  'q [quiet]'     =>  true|false,
 *                      // prints as little as possible
 *  'no-ignore'     =>  true|false,
 *                      // disregard default and svn:ignore property ignores
 *  'username'      =>  'Subversion repository login',
 *  'password'      =>  'Subversion repository password',
 *  'no-auth-cache' =>  true|false,
 *                      // Do not cache authentication tokens
 *  'config-dir'    =>  'Path to a Subversion configuration directory'
 * );
 *
 * </code>
 *
 * Note: Subversion does not offer an XML output option for this subcommand
 *
 * The non-interactive option available on the command-line 
 * svn client may also be set (true|false), but it is set to true by default.
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
 * $svn = VersionControl_SVN::factory(array('status'), $options);
 *
 * // Define any switches and aguments we may need
 * $switches = array('u' => true, 'v' => true);
 * $args = array('/path/to/working/copy/TestProj/trunk');
 *
 * // Run command
 * if ($output = $svn->status->run($args, $switches)) {
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
class VersionControl_SVN_Status extends VersionControl_SVN
{
    /**
     * Valid switches for svn status
     *
     * @var     array
     * @access  public
     */
    var $valid_switches = array('N',
                                'non-recursive',
                                'quiet',
                                'q',
                                'verbose',
                                'v',
                                'show-updates',
                                'u',
                                'no-ignore',
                                'username',
                                'password',
                                'no-auth-cache',
                                'no_auth_cache',
                                'non-interactive',
                                'non_interactive',
                                'config-dir'
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
    var $required_switches = array();
    
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
                    case 'config-dir':
                        $_switches .= "--$switch $val ";
                        break;
                    case 'verbose':
                    case 'non-recursive':
                    case 'no-ignore':
                    case 'show-updates':
                    case 'quiet':
                        if ($val === true) {
                            $_switches .= "--$switch ";
                        }
                        break;
                    case 'N':
                    case 'v':
                    case 'q':
                    case 'u':
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
            case VERSIONCONTROL_SVN_FETCHMODE_ARRAY:
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

// }}}
?>