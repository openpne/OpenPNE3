<?php
/**
 * The SVN list plugin generator for both PEAR_PackageFileManager,
 * and PEAR_PackageFileManager2 classes.
 *
 * PHP versions 5 and 7
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Arnaud Limbourg <arnaud@limbourg.com>
 * @author    Tim Jackson <tim@timj.co.uk>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     File available since Release 1.0.0alpha1
 */

require_once 'PEAR/PackageFileManager/File.php';

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
 * @author    Arnaud Limbourg <arnaud@limbourg.com>
 * @author    Tim Jackson <tim@timj.co.uk>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @version   Release: 1.0.4
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     Class available since Release 1.0.0alpha1
 */
class PEAR_PackageFileManager_Svn extends PEAR_PackageFileManager_File
{
    function __construct($options)
    {
        parent::__construct($options);
    }

    /**
     * Return a list of all files in the SVN repository
     *
     * This function is like {@link parent::dirList()} except
     * that instead of retrieving a regular filelist, it first
     * retrieves a listing of all the .svn/entries files in
     * $directory and all of the subdirectories.  Then, it
     * reads the entries file, and creates a listing of files
     * that are a part of the Subversion checkout.  No check is
     * made to see if they have been modified, but removed files
     * are ignored.
     *
     * @param string $directory full path to the directory you want the list of
     *
     * @access protected
     * @return array list of files in a directory
     * @uses   _recurDirList()
     * @uses   _readSVNEntries()
     */
    function dirList($directory)
    {
        static $in_recursion = false;
        if ($in_recursion) {
            return parent::dirList($directory);
        }

        // include only .svn/entries files
        // since subversion keeps its data in a hidden
        // directory we must force PackageFileManager to
        // consider hidden directories.
        $this->_options['addhiddenfiles'] = true;
        $this->_setupIgnore(array('*/.svn/entries'), 0);
        $this->_setupIgnore(array(), 1);
        $in_recursion = true;
        $entries = parent::dirList($directory);
        $in_recursion = false;

        if (!$entries || !is_array($entries)) {
            $code = PEAR_PACKAGEFILEMANAGER_PLUGINS_NOSVNENTRIES;
            return parent::raiseError($code, $directory);
        }
        return $this->_readSVNEntries($entries);
    }

    /**
     * Iterate over the SVN Entries files, and retrieve every
     * file in the repository
     *
     * @param array $entries array of full paths to .svn/entries files
     *
     * @return array
     * @uses _getSVNEntries()
     * @access private
     */
    function _readSVNEntries($entries)
    {
        $ret = array();
        $ignore = $this->_options['ignore'];
        // implicitly ignore packagefile
        $ignore[] = $this->_options['packagefile'];
        $include  = $this->_options['include'];
        $this->ignore = array(false, false);
        $this->_setupIgnore($ignore, 1);
        $this->_setupIgnore($include, 0);
        foreach ($entries as $entry) {
            $directory = @dirname(@dirname($entry));
            if (!$directory) {
                continue;
            }
            $d = $this->_getSVNEntries($entry);
            if (!is_array($d)) {
                continue;
            }

            foreach ($d as $entry) {
                if ($ignore) {
                    if ($this->_checkIgnore($entry,
                          $directory . DIRECTORY_SEPARATOR . $entry, 1)) {
                        continue;
                    }
                }

                if ($include) {
                    if ($this->_checkIgnore($entry,
                          $directory . DIRECTORY_SEPARATOR . $entry, 0)) {
                        continue;
                    }
                }
                $ret[] = $directory . DIRECTORY_SEPARATOR . $entry;
            }
        }
        return $ret;
    }

    /**
     * Retrieve the entries in a .svn/entries file
     *
     * Uses XML_Tree to parse the XML subversion file
     *
     * It keeps only files, excluding directories. It also
     * makes sure no deleted file in included.
     *
     * @param string $svnentriesfilename full path to a .svn/entries file
     *
     * @return array an array with full paths to files
     * @uses   PEAR::XML_Tree
     * @access private
     */
    function _getSVNEntries($filename)
    {
        $content = file_get_contents($filename);
        if (substr($content, 0, 5) != '<?xml') {
            // Not XML; assume newer (>= SVN 1.4) SVN entries format
            // http://svn.apache.org/repos/asf/subversion/trunk/subversion/libsvn_wc/README

            // The directory entries are seperated by #0c; look for the first #0c
            // The hex GUID (xxxx-xxxx-xxxx-xxxx-xxxx) may not always be set
            // The list of files follows this
            if (!preg_match('/\x0c\n(.*)$/ms', $content, $matches)) {
                return false;
            }

            // Each file entry seems to look something like this:
            // [filename]
            // [type of file e.g. "dir", "file"]
            // [varying number of \n]
            // [optional "deleted" string]
            $files = explode("\x0c", trim($matches[1]));
            foreach ($files as $file) {
                $lines = explode("\n", trim($file));
                if (isset($lines[1]) && $lines[1] == 'file') {
                    $deleted = false;
                    foreach ($lines as $line) {
                        // 'deleted' means it's already gone
                        // 'delete' means it's marked as ready to delete
                        if ($line == 'deleted' || $line == 'delete') {
                            $deleted = true;
                        }
                    }

                    if (!$deleted) {
                        $entries[] = $lines[0];
                    }
                }
            }
        } elseif (function_exists('simplexml_load_string')) {
            // this breaks simplexml because "svn:" is an invalid namespace, so strip it
            $content = str_replace('xmlns="svn:"', '', $content);
            $all     = simplexml_load_string($content);
            $entries = array();
            foreach ($all->entry as $entry) {
                if ($entry['kind'] == 'file') {
                    // 'deleted' means it's already gone
                    // 'delete' means it's marked as ready to delete
                    if (isset($entry['deleted']) || isset($entry['delete'])) {
                        continue;
                    }
                    array_push($entries, $entry['name']);
                }
            }
        } else {
            require_once 'XML/Unserializer.php';
            $options = array(
                XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE    => true,
                XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY => false
            );
            $unserializer = new XML_Unserializer($options);
            $status = $unserializer->unserialize($content);
            if (PEAR::isError($status)) {
                return false;
            }
            $tree = $unserializer->getUnserializedData();

            // loop through the xml tree and keep only valid entries being files
            $entries = array();
            foreach ($tree['entry'] as $entry) {
                if ($entry['kind'] == 'file') {
                    // 'deleted' means it's already gone
                    // 'delete' means it's marked as ready to delete
                    if (isset($entry['deleted']) || isset($entry['delete'])) {
                        continue;
                    }
                    array_push($entries, $entry['name']);
                }
            }

            unset($unserializer, $tree);
        }

        if (isset($entries) && is_array($entries)) {
            return $entries;
        }

        return false;
    }
}
