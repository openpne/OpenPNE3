<?php
/**
 * Base class for all the plugins
 *
 * PHP versions 5 and 7
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Helgi Þormar Þorbjörnsson <helgi@php.net>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     File available since Release 1.0.0alpha1
 */

/**#@+
 * Error Codes
 */
define('PEAR_PACKAGEFILEMANAGER_PLUGINS_NOCVSENTRIES', 12);
define('PEAR_PACKAGEFILEMANAGER_PLUGINS_DIR_DOESNT_EXIST', 13);
define('PEAR_PACKAGEFILEMANAGER_PLUGINS_NO_FILES', 20);
define('PEAR_PACKAGEFILEMANAGER_PLUGINS_IGNORED_EVERYTHING', 21);
define('PEAR_PACKAGEFILEMANAGER_PLUGINS_NOSVNENTRIES', 32);
/**#@-*/
/**
 * Error messages
 * @global array $GLOBALS['_PEAR_PACKAGEFILEMANAGER_PLUGINS_ERRORS']
 */
$GLOBALS['_PEAR_PACKAGEFILEMANAGER_PLUGINS_ERRORS'] =
array(
    PEAR_PACKAGEFILEMANAGER_PLUGINS_NOCVSENTRIES =>
        'Directory "%s" is not a CVS directory (it must have the CVS/Entries file)',
    PEAR_PACKAGEFILEMANAGER_PLUGINS_DIR_DOESNT_EXIST =>
        'Package source base directory "%s" doesn\'t exist or isn\'t a directory',
    PEAR_PACKAGEFILEMANAGER_PLUGINS_NO_FILES =>
        'No files found, check the path "%s"',
    PEAR_PACKAGEFILEMANAGER_PLUGINS_IGNORED_EVERYTHING =>
        'No files left, check the path "%s" and ignore option "%s"',
    PEAR_PACKAGEFILEMANAGER_PLUGINS_NOSVNENTRIES =>
        'Directory "%s" is not a SVN directory (it must have the .svn/entries file)',
);

/**
 * Generate a file list from a Subversion checkout
 *
 * Largely based on the CVS class, modified to suit
 * subversion organization.
 *
 * Note that this will <b>NOT</b> work on a
 * repository, only on a checked out Subversion module
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Helgi Þormar Þorbjörnsson <helgi@php.net>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @version   Release: 1.0.4
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     Class available since Release 1.0.0alpha1
 */
class PEAR_PackageFileManager_Plugins
{
    /**
     * @var array
     * @access protected
     */
    var $_options = array();

    /**
     * Utility function to shorten error generation code
     *
     * {@source}
     *
     * @param integer $code error code
     * @param string  $i1   (optional) additional specific error info #1
     * @param string  $i2   (optional) additional specific error info #2
     *
     * @return PEAR_Error
     * @static
     * @access public
     * @since 1.0.0alpha1
     */
    public static function raiseError($code, $i1 = '', $i2 = '')
    {
        return PEAR::raiseError('PEAR_PackageFileManager_Plugins Error: ' .
                    sprintf($GLOBALS['_PEAR_PACKAGEFILEMANAGER_PLUGINS_ERRORS'][$code],
                    $i1, $i2), $code);
    }

    /**
     * Merge a new set of options (as an array) to the currently set
     * options
     *
     * @param $options array Options to merge with the current options
     * @return void
     */
    function setOptions($options)
    {
        $this->_options = array_merge($this->_options, $options);
    }
}
