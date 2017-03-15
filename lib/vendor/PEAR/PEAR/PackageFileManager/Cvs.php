<?php
/**
 * The CVS list plugin generator for both PEAR_PackageFileManager,
 * and PEAR_PackageFileManager2 classes.
 *
 * PHP versions 5 and 7
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Greg Beaver <cellog@php.net>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     File available since Release 1.0.0alpha1
 */

require_once 'PEAR/PackageFileManager/File.php';

/**
 * Generate a file list from a CVS checkout.
 *
 * Note that this will <b>NOT</b> work on a
 * repository, only on a checked out CVS module
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Greg Beaver <cellog@php.net>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @version   Release: 1.0.4
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     Class available since Release 1.0.0alpha1
 */
class PEAR_PackageFileManager_CVS extends PEAR_PackageFileManager_File
{
    /**
     * List of CVS-specific files that may exist in CVS but should be
     * ignored when building the package's file list.
     * @var array
     * @access private
     */
    var $_cvsIgnore = array('.cvsignore');

    function __construct($options)
    {
        parent::__construct($options);
    }

    /**
     * Return a list of all files in the CVS repository
     *
     * This function is like {@link parent::dirList()} except
     * that instead of retrieving a regular filelist, it first
     * retrieves a listing of all the CVS/Entries files in
     * $directory and all of the subdirectories.  Then, it
     * reads the Entries file, and creates a listing of files
     * that are a part of the CVS repository.  No check is
     * made to see if they have been modified, but newly
     * added or removed files are ignored.
     *
     * @param string $directory full path to the directory you want the list of
     *
     * @return array list of files in a directory
     * @uses _recurDirList()
     * @uses _readCVSEntries()
     */
    function dirList($directory)
    {
        static $in_recursion = false;
        if ($in_recursion) {
            return parent::dirList($directory);
        }

        // include only CVS/Entries files
        $this->_setupIgnore(array('*/CVS/Entries'), 0);
        $this->_setupIgnore(array(), 1);
        $in_recursion = true;
        $entries      = parent::dirList($directory);
        $in_recursion = false;

        if (!$entries || !is_array($entries)) {
            $code = PEAR_PACKAGEFILEMANAGER_PLUGINS_NOCVSENTRIES;
            return parent::raiseError($code, $directory);
        }

        return $this->_readCVSEntries($entries);
    }

    /**
     * Iterate over the CVS Entries files, and retrieve every
     * file in the repository
     *
     * @param array $entries array of full paths to CVS/Entries files
     *
     * @uses _getCVSEntries()
     * @uses _isCVSFile()
     * @return array
     * @access private
     */
    function _readCVSEntries($entries)
    {
        $ret    = array();
        if (!isset($this->_options['ignore'])) {
            $this->_options['ignore'] = false;
        }
        $ignore = array_merge((array) $this->_options['ignore'], $this->_cvsIgnore);
        // implicitly ignore packagefile
        $ignore[] = $this->_options['packagefile'];
        $include  = $this->_options['include'];

        $this->ignore = array(false, false);
        $this->_setupIgnore($ignore, 1);
        $this->_setupIgnore($include, 0);
        foreach ($entries as $cvsentry) {
            $directory = @dirname(@dirname($cvsentry));
            if (!$directory) {
                continue;
            }

            $d = $this->_getCVSEntries($cvsentry);
            if (!is_array($d)) {
                continue;
            }

            foreach ($d as $entry) {
                if ($ignore) {
                    if ($this->_checkIgnore($this->_getCVSFileName($entry),
                          $directory . DIRECTORY_SEPARATOR . $this->_getCVSFileName($entry), 1)) {
                        continue;
                    }
                }

                if ($include) {
                    if ($this->_checkIgnore($this->_getCVSFileName($entry),
                          $directory . DIRECTORY_SEPARATOR . $this->_getCVSFileName($entry), 0)) {
                        continue;
                    }
                }

                if ($this->_isCVSFile($entry)) {
                    $ret[] = $directory . DIRECTORY_SEPARATOR . $this->_getCVSFileName($entry);
                }
            }
        }

        return $ret;
    }

    /**
     * Retrieve the filename from an entry
     *
     * This method assumes that the entry is a file,
     * use _isCVSFile() to verify before calling
     *
     * @param string $cvsentry a line in a CVS/Entries file
     *
     * @return string the filename (no path information)
     * @access private
     */
    function _getCVSFileName($cvsentry)
    {
        $stuff = explode('/', $cvsentry);
        array_shift($stuff);
        return array_shift($stuff);
    }

    /**
     * Retrieve the entries in a CVS/Entries file
     *
     * @param string $cvsentryfilename full path to a CVS/Entries file
     *
     * @return array each line of the entries file, output of file()
     * @uses function file()
     * @access private
     */
    function _getCVSEntries($cvsentryfilename)
    {
        $cvsfile = @file($cvsentryfilename);
        if (is_array($cvsfile)) {
            return $cvsfile;
        }

        return false;
    }

    /**
     * Check whether an entry is a file or a directory
     *
     * @param string $cvsentry a line in a CVS/Entries file
     *
     * @return boolean
     * @access private
     */
    function _isCVSFile($cvsentry)
    {
        // make sure we ignore entries that have either been removed or added,
        // but not committed yet
        return $cvsentry{0} == '/' && !strpos($cvsentry, 'dummy timestamp');
    }
}
