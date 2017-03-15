<?php
/**
 * The File list plugin generator for both PEAR_PackageFileManager,
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

require_once 'PEAR/PackageFileManager/Plugins.php';

/**
 * Retrieve the files from a directory listing
 *
 * This class is used to retrieve a raw directory
 * listing.  Use the {@link PEAR_PackageFileManager_CVS}
 * class to only retrieve the contents of a cvs
 * repository when generating the package.xml
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
class PEAR_PackageFileManager_File extends PEAR_PackageFileManager_Plugins
{
    /**
     * @access private
     * @var array|false
     */
    var $ignore = false;

    /**
     * If we are on windows
     * @access private
     * @var boolean
     */
    var $windows = false;

    /**
     * Set up the File filelist generator
     *
     * 'ignore' and 'include' are the only options that this class uses.  See
     * {@link PEAR_PackageFileManager::setOptions()} for
     * more information and formatting of this option
     *
     * @param array  $options list of generation options
     *
     * @return void
     */
    function __construct($options)
    {
        $this->windows = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN';
        $this->setOptions($options);
    }

    /**
     * Generate the <filelist></filelist> section
     * of the package file.
     *
     * This function performs the backend generation of the array
     * containing all files in this package
     *
     * @return array
     */
    function getFileList()
    {
        $package_directory = $this->_options['packagedirectory'];
        $ignore = $this->_options['ignore'];
        // implicitly ignore packagefile
        $ignore[] = $this->_options['packagefile'];
        if ($this->_options['packagefile'] == 'package.xml') {
            // ignore auto-generated package2.xml from PEAR 1.4.0
            $ignore[] = 'package2.xml';
        }

        $dir = $package_directory;
        if ($dir{strlen($dir) - 1} === DIRECTORY_SEPARATOR) {
            $dir = substr($package_directory, 0, strlen($package_directory) - 1);
        }

        $include = $this->_options['include'];
        $this->ignore = array(false, false);
        $this->_setupIgnore($ignore, 1);
        $this->_setupIgnore($include, 0);
        $allfiles = $this->dirList($dir);
        if (PEAR::isError($allfiles)) {
            return $allfiles;
        }

        if (!count($allfiles)) {
            return parent::raiseError(PEAR_PACKAGEFILEMANAGER_PLUGINS_NO_FILES, $dir);
        }

        $struc = array();
        $package_directory_realpath = realpath($package_directory);
        if (DIRECTORY_SEPARATOR != substr($package_directory_realpath, -1, 1)) {
            $package_directory_realpath .= DIRECTORY_SEPARATOR;
        }

        foreach ($allfiles as $file) {
            $ps   = str_replace(DIRECTORY_SEPARATOR, '/', $package_directory_realpath);
            $path = substr(dirname($file), strlen($ps));
            if (!$path) {
                $path = '/';
            }

            $stupidassphp5_1 = explode('.', $file);
            $ext = array_pop($stupidassphp5_1);
            if (strlen($ext) === strlen($file)) {
                $ext = '';
            }

            $struc[$path][] = array(
                'file' => basename($file),
                'ext' => $ext,
                'path' => (($path == '/') ? basename($file) : $path . '/' . basename($file)),
                'fullpath' => $file
            );
        }

        if (!count($struc)) {
            $newig = implode($this->_options['ignore'], ', ');
            return parent::raiseError(PEAR_PACKAGEFILEMANAGER_PLUGINS_IGNORED_EVERYTHING,
                substr($package_directory, 0, strlen($package_directory) - 1), $newig);
        }

        uksort($struc, 'strnatcasecmp');
        foreach ($struc as $key => $ind) {
            usort($ind, array($this, 'sortfiles'));
            $struc[$key] = $ind;
        }

        $tempstruc = $struc;
        if (!isset($tempstruc['/'])) {
            $tempstruc['/'] = array();
        }

        $struc = array('/' => $tempstruc['/']);
        $bv = 0;
        foreach ($tempstruc as $key => $ind) {
            $save = $key;
            if ($key != '/') {
                $struc['/'] = $this->_setupDirs($struc['/'], explode('/', $key), $tempstruc[$key]);
            }
        }
        uksort($struc['/'], array($this, 'mystrucsort'));

        return $struc;
    }

    /**
     * Retrieve a listing of every file in $directory and
     * all subdirectories.
     *
     * The return format is an array of full paths to files
     *
     * @param string $directory full path to the directory you want the list of
     *
     * @access protected
     * @return array list of files in a directory
     * @throws PEAR_PACKAGEFILEMANAGER_PLUGINS_DIR_DOESNT_EXIST
     */
    function dirList($directory)
    {
        if (!@is_dir($directory)) {
            return parent::raiseError(PEAR_PACKAGEFILEMANAGER_PLUGINS_DIR_DOESNT_EXIST, $directory);
        }

        $ret = array();
        $d = @dir($directory); // thanks to Jason E Sweat (jsweat@users.sourceforge.net) for fix
        while ($d && false !== ($entry = $d->read())) {
            if ($this->_testFile($directory, $entry)) {
                $de = $directory . '/' . $entry;
                if (is_file($de)) {
                    // if include option was set, then only pass included files
                    if ($this->ignore[0] && $this->_checkIgnore($entry, $de, 0)) {
                        continue;
                    }
                    // if ignore option was set, then only pass included files
                    if ($this->ignore[1] && $this->_checkIgnore($entry, $de, 1)) {
                        continue;
                    }
                    $ret[] = $de;
                }

                if (is_dir($de)) {
                    $tmp = $this->dirList($de);
                    if (is_array($tmp)) {
                        foreach ($tmp as $ent) {
                            $ret[] = $ent;
                        }
                    }
                }
            }
        }

        if ($d) {
            $d->close();
        }

        usort($ret, array($this, 'mystrucsort'));
        return $ret;
    }

    /**
     * Test whether an entry should be processed.
     *
     * Normally, it ignores all files and directories that begin with "."  addhiddenfiles option
     * instead only ignores "." and ".." entries
     *
     * @param string $directory directory name of entry
     * @param string $entry     name
     *
     * @return bool
     * @access private
     */
    function _testFile($directory, $entry)
    {
        if ($this->_options['addhiddenfiles']) {
            return is_file($directory . '/' . $entry) || (is_dir($directory . '/' . $entry) && !in_array($entry, array('.', '..')));
        }

        return $entry{0} != '.';
    }

    /**
     * Tell whether to ignore a file or a directory
     * allows * and ? wildcards
     *
     * @param string $file   just the file name of the file or directory,
     *                          in the case of directories this is the last dir
     * @param string $path   the full path
     * @param bool   $return value to return if regexp matches.  Set this to
     *                            false to include only matches, true to exclude
     *                            all matches
     *
     * @return bool  true if $path should be ignored, false if it should not
     * @access private
     */
    function _checkIgnore($file, $path, $return = 1)
    {
        if ($this->windows && file_exists($path)) {
            $path = realpath($path);
        }

        if (DIRECTORY_SEPARATOR == '\\') {
          $path = strtr($path, '/', '\\');
        } else {
          $path = strtr($path, '\\', '/');
        }

        if (is_array($this->ignore[$return])) {
            foreach ($this->ignore[$return] as $match) {
                // match is an array if the ignore parameter was a /path/to/pattern
                if (is_array($match)) {
                    // check to see if the path matches with a path delimiter appended
                    preg_match('/^' . strtoupper($match[0]).'$/', strtoupper($path) . DIRECTORY_SEPARATOR, $find);
                    if (!count($find)) {
                        // check to see if it matches without an appended path delimiter
                        preg_match('/^' . strtoupper($match[0]).'$/', strtoupper($path), $find);
                    }

                    if (count($find)) {
                        // check to see if the file matches the file portion of the regex string
                        preg_match('/^' . strtoupper($match[1]).'$/', strtoupper($file), $find);
                        if (count($find)) {
                            return $return;
                        }
                    }

                    // check to see if the full path matches the regex
                    preg_match('/^' . strtoupper($match[0]).'$/',
                               strtoupper($path . DIRECTORY_SEPARATOR . $file), $find);
                    if (count($find)) {
                        return $return;
                    }

                    // user is trying a regex with no glob, lets give him the full path to match against
                    if (!isset($this->_options['packagedirectory'])) {
                        return !$return;
                    }

                    $t = $this->_getRegExpableSearchString($this->_options['packagedirectory']);
                    preg_match('/^' . strtoupper($t . $match[0]).'$/', strtoupper($path), $find);
                    if (count($find)) {
                        return $return;
                    }
                } else {
                    // ignore parameter was just a pattern with no path delimiters
                    // check it against the path
                    preg_match('/^' . strtoupper($match).'$/', strtoupper($path), $find);
                    if (count($find)) {
                        return $return;
                    }

                    // check it against the file only
                    preg_match('/^' . strtoupper($match).'$/', strtoupper($file), $find);
                    if (count($find)) {
                        return $return;
                    }
                }
            }
        }

        return !$return;
    }

    /**
     * Construct the {@link $ignore} array
     *
     * @param array $ignore strings of files/paths/wildcards to ignore
     * @param bool  $index  0 = files to include, 1 = files to ignore
     *
     * @return void
     * @access private
     */
    function _setupIgnore($ignore, $index)
    {
        if (!is_array($ignore)) {
            $this->ignore[$index] = false;
            return;
        }

        $ig = array();
        for ($i = 0, $ic = count($ignore); $i < $ic; $i++) {
            $ignore[$i] = strtr($ignore[$i], '\\', '/');
            $ignore[$i] = str_replace('//', '/', $ignore[$i]);

            if (!empty($ignore[$i])) {
                if (!is_numeric(strpos($ignore[$i], '/'))) {
                    $ig[] = $this->_getRegExpableSearchString($ignore[$i]);
                } else {
                    /*
                     People tend to forgot to add * when they want to ignore
                     a whole dir so we try to discover it for them
                     Make sure the char before the last / is not * as adding *
                     after a / as well is not optimal
                    */
                    $one = strrpos($ignore[$i], '/');
                    $len = strlen($ignore[$i]);
                    if ($one !== false && $len-1 == $one &&
                        ($len > 1 && $ignore[$i][$len-2] != '*')
                    ) {
                        $ignore[$i] .= '*';
                    }

                    if (basename($ignore[$i]) . '/' == $ignore[$i]) {
                        $ig[] = $this->_getRegExpableSearchString($ignore[$i]);
                    } else {
                        $ig[] = array($this->_getRegExpableSearchString($ignore[$i]),
                                  $this->_getRegExpableSearchString(basename($ignore[$i])));
                    }
                }
            }
        }

        $this->ignore[$index] = count($ig) ? $ig : false;
    }

    /**
     * Converts $s into a string that can be used with preg_match
     *
     * @param string $s string with wildcards ? and *
     *
     * @return string converts * to .*, ? to ., etc.
     * @access private
     */
    function _getRegExpableSearchString($s)
    {
        $y = '\/';
        if (DIRECTORY_SEPARATOR == '\\') {
            $y = '\\\\';
        }

        $s = str_replace('/', DIRECTORY_SEPARATOR, $s);
        $x = strtr($s, array('?' => '.', '*' => '.*', '.' => '\\.', '\\' => '\\\\', '/' => '\\/',
                             '[' => '\\[', ']' => '\\]', '-' => '\\-'));

        if (strpos($s, DIRECTORY_SEPARATOR) !== false &&
              strrpos($s, DIRECTORY_SEPARATOR) === strlen($s) - 1) {
            $x = "(?:.*$y$x?.*|$x.*)";
        }

        return $x;
    }

    /**
     * Recursively move contents of $struc into associative array
     *
     * The contents of $struc have many indexes like 'dir/subdir/subdir2'.
     * This function converts them to
     * array('dir' => array('subdir' => array('subdir2')))
     *
     * @param array $struc    is array('dir' => array of files in dir,
     *              'dir/subdir' => array of files in dir/subdir,...)
     * @param array $dir      array form of 'dir/subdir/subdir2' array('dir','subdir','subdir2')
     * @param array $contents
     *
     * @return array same as struc but with array('dir' =>
     *              array(file1,file2,'subdir' => array(file1,...)))
     * @access private
     */
    function _setupDirs($struc, $dir, $contents)
    {
        if (!count($dir)) {
            foreach ($contents as $dir => $files) {
                if (is_string($dir) && strpos($dir, '/')) {
                    $a = $contents[$dir];
                    unset($contents[$dir]);
                    $b = explode('/', $dir);
                    $c = array_shift($b);
                    if (isset($contents[$c])) {
                        $contents[$c] = $this->_setDir($contents[$c], $this->_setupDirs(array(), $b, $a));
                    } else {
                        $contents[$c] = $this->_setupDirs(array(), $b, $a);
                    }
                }
            }

            return $contents;
        }

        $me = array_shift($dir);
        if (!isset($struc[$me])) {
            $struc[$me] = array();

        }
        $struc[$me] = $this->_setupDirs($struc[$me], $dir, $contents);
        return $struc;
    }

    /**
     * Recursively add all the subdirectories of $contents to $dir
     * without erasing anything in $dir
     *
     * @param array $dir
     * @param array $contents
     *
     * @return array processed $dir
     * @access private
     */
    function _setDir($dir, $contents)
    {
        while (list($one,$two) = each($contents)) {
            if (isset($dir[$one])) {
                $dir[$one] = $this->_setDir($dir[$one], $contents[$one]);
            } else {
                $dir[$one] = $two;
            }
        }
        return $dir;
    }

    /**#@+
     * Sorting functions for the file list
     *
     * @param string $a
     * @param string $b
     *
     * @access private
     */
    function sortfiles($a, $b)
    {
        return strnatcasecmp($a['file'], $b['file']);
    }

    function mystrucsort($a, $b)
    {
        if (is_numeric($a) && is_string($b)) return 1;
        if (is_numeric($b) && is_string($a)) return -1;
        if (is_numeric($a) && is_numeric($b)) {
            if ($a > $b) return 1;
            if ($a < $b) return -1;
            if ($a == $b) return 0;
        }
        return strnatcasecmp($a, $b);
    }
    /**#@-*/
}
