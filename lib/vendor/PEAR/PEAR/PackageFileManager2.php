<?php
/**
 * PEAR_PackageFileManager2, like PEAR_PackageFileManager, is designed to
 * create and manipulate package.xml version 2.0.
 *
 * PHP versions 5 and 7
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager2
 * @author    Greg Beaver <cellog@php.net>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @link      http://pear.php.net/package/PEAR_PackageFileManager2
 * @since     File available since Release 1.0.0alpha1
 */
/**
 * PEAR Packagefile parser
 */
require_once 'PEAR/PackageFile.php';

/**
 * PEAR Packagefile version 2.0
 */
require_once 'PEAR/PackageFile/v2/rw.php';
/**#@+
 * Error Codes
 */
define('PEAR_PACKAGEFILEMANAGER2_NOPKGDIR',                     3);
define('PEAR_PACKAGEFILEMANAGER2_NOBASEDIR',                    4);
define('PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND',           5);
define('PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND_ANYWHERE',  6);
define('PEAR_PACKAGEFILEMANAGER2_CANTWRITE_PKGFILE',            7);
define('PEAR_PACKAGEFILEMANAGER2_DEST_UNWRITABLE',              8);
define('PEAR_PACKAGEFILEMANAGER2_CANTCOPY_PKGFILE',             9);
define('PEAR_PACKAGEFILEMANAGER2_CANTOPEN_TMPPKGFILE',         10);
define('PEAR_PACKAGEFILEMANAGER2_PATH_DOESNT_EXIST',           11);
define('PEAR_PACKAGEFILEMANAGER2_DIR_DOESNT_EXIST',            13);
define('PEAR_PACKAGEFILEMANAGER2_RUN_SETOPTIONS',              14);
define('PEAR_PACKAGEFILEMANAGER2_NO_FILES',                    20);
define('PEAR_PACKAGEFILEMANAGER2_IGNORED_EVERYTHING',          21);
define('PEAR_PACKAGEFILEMANAGER2_INVALID_PACKAGE',             22);
define('PEAR_PACKAGEFILEMANAGER2_INVALID_REPLACETYPE',         23);
define('PEAR_PACKAGEFILEMANAGER2_INVALID_ROLE',                24);
define('PEAR_PACKAGEFILEMANAGER2_CVS_PACKAGED',                26);
define('PEAR_PACKAGEFILEMANAGER2_NO_PHPCOMPATINFO',            27);
define('PEAR_PACKAGEFILEMANAGER2_INVALID_POSTINSTALLSCRIPT',   28);
define('PEAR_PACKAGEFILEMANAGER2_PKGDIR_NOTREAL',              29);
define('PEAR_PACKAGEFILEMANAGER2_OUTPUTDIR_NOTREAL',           30);
define('PEAR_PACKAGEFILEMANAGER2_PATHTOPKGDIR_NOTREAL',        31);
/**#@-*/
/**
 * Error messages
 * @global array $GLOBALS['_PEAR_PACKAGEFILEMANAGER2_ERRORS']
 * @access private
 */
$GLOBALS['_PEAR_PACKAGEFILEMANAGER2_ERRORS'] =
array(
    'en' =>
    array(
        PEAR_PACKAGEFILEMANAGER2_NOPKGDIR =>
            'Package source base directory (option \'packagedirectory\') must be ' .
            'specified in PEAR_PackageFileManager2 setOptions',
        PEAR_PACKAGEFILEMANAGER2_PKGDIR_NOTREAL =>
            'Package source base directory (option \'packagedirectory\') must be ' .
            'an existing directory (was "%s")',
        PEAR_PACKAGEFILEMANAGER2_PATHTOPKGDIR_NOTREAL =>
            'Path to a Package file to read in (option \'pathtopackagefile\') must be ' .
            'an existing directory (was "%s")',
        PEAR_PACKAGEFILEMANAGER2_OUTPUTDIR_NOTREAL =>
            'output directory (option \'outputdirectory\') must be ' .
            'an existing directory (was "%s")',
        PEAR_PACKAGEFILEMANAGER2_NOBASEDIR =>
            'Package install base directory (option \'baseinstalldir\') must be ' .
            'specified in PEAR_PackageFileManager2 setOptions',
        PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND =>
            'Base class "%s" can\'t be located',
        PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND_ANYWHERE =>
            'Base class "%s" can\'t be located in default or user-specified directories',
        PEAR_PACKAGEFILEMANAGER2_CANTWRITE_PKGFILE =>
            'Failed to write package.xml file to destination directory',
        PEAR_PACKAGEFILEMANAGER2_DEST_UNWRITABLE =>
            'Destination directory "%s" is unwritable',
        PEAR_PACKAGEFILEMANAGER2_CANTCOPY_PKGFILE =>
            'Failed to copy package.xml.tmp file to package.xml',
        PEAR_PACKAGEFILEMANAGER2_CANTOPEN_TMPPKGFILE =>
            'Failed to open temporary file "%s" for writing',
        PEAR_PACKAGEFILEMANAGER2_PATH_DOESNT_EXIST =>
            'package.xml file path "%s" doesn\'t exist or isn\'t a directory',
        PEAR_PACKAGEFILEMANAGER2_DIR_DOESNT_EXIST =>
            'Package source base directory "%s" doesn\'t exist or isn\'t a directory',
        PEAR_PACKAGEFILEMANAGER2_RUN_SETOPTIONS =>
            'Run $managerclass->setOptions() before any other methods',
        PEAR_PACKAGEFILEMANAGER2_NO_FILES =>
            'No files found, check the path "%s"',
        PEAR_PACKAGEFILEMANAGER2_IGNORED_EVERYTHING =>
            'No files left, check the path "%s" and ignore option "%s"',
        PEAR_PACKAGEFILEMANAGER2_INVALID_PACKAGE =>
            'Package validation failed:%s%s',
        PEAR_PACKAGEFILEMANAGER2_INVALID_REPLACETYPE =>
            'Replacement Type must be one of "%s", was passed "%s"',
        PEAR_PACKAGEFILEMANAGER2_INVALID_POSTINSTALLSCRIPT =>
            'Invalid post-install script task: %s',
        PEAR_PACKAGEFILEMANAGER2_INVALID_ROLE =>
            'Invalid file role passed to addRole, must be one of "%s", was passed "%s"',
        PEAR_PACKAGEFILEMANAGER2_CVS_PACKAGED =>
            'path "%path%" contains CVS directory',
        PEAR_PACKAGEFILEMANAGER2_NO_PHPCOMPATINFO =>
            'pear/PHP_CompatInfo is not installed, cannot detect dependencies',
       ),
        // other language translations go here
     );
/**
 * PEAR_PackageFileManager2, like PEAR_PackageFileManager, is designed to
 * create and manipulate package.xml version 2.0.
 *
 * The PEAR_PackageFileManager2 class can work directly with PEAR_PackageFileManager
 * to create parallel package.xml files, version 1.0 and 2.0, that represent the
 * same project, but take advantage of package.xml 2.0-specific features.
 *
 * Like PEAR_PackageFileManager, The PEAR_PackageFileManager2 class uses a plugin system
 * to generate the list of files in a package.  This allows both standard recursive
 * directory parsing (plugin type file) and more intelligent options
 * such as the CVS browser {@link PEAR_PackageFileManager_Cvs}, which
 * grabs all files in a local CVS checkout to create the list, ignoring
 * any other local files.
 *
 * Example usage is similar to PEAR_PackageFileManager:
 * <code>
 * <?php
 * require_once('PEAR/PackageFileManager2.php');
 * PEAR::setErrorHandling(PEAR_ERROR_DIE);
 * //require_once 'PEAR/Config.php';
 * //PEAR_Config::singleton('/path/to/unusualpearconfig.ini');
 * // use the above lines if the channel information is not validating
 * $pfm = new PEAR_PackageFileManager2;
 * // for an existing package.xml use
 * // $pfm = {@link importOptions()} instead
 * $e = $pfm->setOptions(
 * array('baseinstalldir' => 'PhpDocumentor',
 *  'packagedirectory' => 'C:/Web Pages/chiara/phpdoc2/',
 *  'filelistgenerator' => 'cvs', // generate from cvs, use file for directory
 *  'ignore' => array('TODO', 'tests/'), // ignore TODO, all files in tests/
 *  'installexceptions' => array('phpdoc' => '/*'), // baseinstalldir ="/" for phpdoc
 *  'dir_roles' => array('tutorials' => 'doc'),
 *  'exceptions' => array('README' => 'doc', // README would be data, now is doc
 *                        'PHPLICENSE.txt' => 'doc'))); // same for the license
 * $pfm->setPackage('MyPackage');
 * $pfm->setSummary('this is my package');
 * $pfm->setDescription('this is my package description');
 * $pfm->setChannel('mychannel.example.com');
 * $pfm->setAPIVersion('1.0.0');
 * $pfm->setReleaseVersion('1.2.1');
 * $pfm->setReleaseStability('stable');
 * $pfm->setAPIStability('stable');
 * $pfm->setNotes("We've implemented many new and exciting features");
 * $pfm->setPackageType('php'); // this is a PEAR-style php script package
 * $pfm->addRelease(); // set up a release section
 * $pfm->setOSInstallCondition('windows');
 * $pfm->addInstallAs('pear-phpdoc.bat', 'phpdoc.bat');
 * $pfm->addIgnoreToRelease('pear-phpdoc');
 * $pfm->addRelease(); // add another release section for all other OSes
 * $pfm->addInstallAs('pear-phpdoc', 'phpdoc');
 * $pfm->addIgnoreToRelease('pear-phpdoc.bat');
 * $pfm->addRole('pkg', 'doc'); // add a new role mapping
 * $pfm->setPhpDep('4.2.0');
 * $pfm->setPearinstallerDep('1.4.0a12');
 * $pfm->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
 * $pfm->setLicense('PHP License', 'http://www.php.net/license');
 * $pfm->generateContents(); // create the <contents> tag
 * // replace @PHP-BIN@ in this file with the path to php executable!  pretty neat
 * $test->addReplacement('pear-phpdoc', 'pear-config', '@PHP-BIN@', 'php_bin');
 * $test->addReplacement('pear-phpdoc.bat', 'pear-config', '@PHP-BIN@', 'php_bin');
 * $pkg = &$pfm->exportCompatiblePackageFile1(); // get a PEAR_PackageFile object
 * // note use of {@link debugPackageFile()} - this is VERY important
 * if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
 *     $pkg->writePackageFile();
 *     $pfm->writePackageFile();
 * } else {
 *     $pkg->debugPackageFile();
 *     $pfm->debugPackageFile();
 * }
 * ?>
 * </code>
 *
 * In addition, a package.xml file can now be generated from
 * scratch, with the usage of new options package, summary, description, and
 * the use of the {@link addLead(), addDeveloper(), addContributor(), addHelper()} methods
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager2
 * @author    Greg Beaver <cellog@php.net>
 * @copyright 2003-2015 The PEAR Group
 * @license   New BSD, Revised
 * @version   Release: 1.0.4
 * @link      http://pear.php.net/package/PEAR_PackageFileManager2
 * @since     Class available since Release 1.0.0alpha1
 */
class PEAR_PackageFileManager2 extends PEAR_PackageFile_v2_rw
{
    /**
     * Format: array(array(regexp-ready string to search for whole path,
     * regexp-ready string to search for basename of ignore strings),...)
     * @var false|array
     * @access private
     * @since  1.0.0a1
     */
    var $_ignore = false;

    /**
     * Contents of the package.xml file
     * @var PEAR_PackageFile_v2
     * @access private
     * @since  1.0.0a1
     */
    var $_packageXml = false;

    /**
     * List of warnings
     * @var array
     * @access private
     * @since  1.0.0a1
     */
    var $_warningStack = array();

    /**
     * flag used to determine whether to use PHP_CompatInfo to detect deps
     * @var boolean
     * @access private
     * @since  1.0.0a1
     */
    var $_detectDependencies = false;

    /**
     * The original contents of the old package.xml, if any
     * @var PEAR_PackageFile_v2|false
     * @access private
     * @since  1.0.0a1
     */
    var $_oldPackageFile = false;

    /**
     * Collection of subpackages
     *
     * This collection is used to handle coordination between the contents of
     * related packages whose files reside in the same development directory
     * @var array
     * @access private
     * @since  1.0.0a1
     */
    var $_subpackages = array();

    /**
     * List of package.xml generation options
     * @var string
     * @access private
     * @since  1.0.0a1
     */
    var $_options = array(
                      'packagefile'       => 'package.xml',
                      'filelistgenerator' => 'file',
                      'license'           => 'New BSD License',
                      'baseinstalldir'    => '',
                      'changelogoldtonew' => true,
                      'roles' =>
                        array(
                            'h'    => 'src',
                            'c'    => 'src',
                            'cpp'  => 'src',
                            'in'   => 'src',
                            'm4'   => 'src',
                            'w32'  => 'src',
                            'dll'  => 'ext',
                            'php'  => 'php',
                            'html' => 'doc',
                            '*'    => 'data',
                             ),
                      'dir_roles' =>
                        array(
                            'docs'     => 'doc',
                            'examples' => 'doc',
                            'tests'    => 'test',
                            'scripts'  => 'script',
                             ),
                      'exceptions'        => array(),
                      'installexceptions' => array(),
                      'ignore'  => array(),
                      'include' => false,
                      'notes'   => '',
                      'changelognotes'    => false,
                      'outputdirectory'   => false,
                      'pathtopackagefile' => false,
                      'lang' => 'en',
                      'configure_options'       => array(),
                      'replacements'            => array(),
                      'globalreplacements'      => array(),
                      'globalreplaceexceptions' => array(),
                      'simpleoutput'      => false,
                      'addhiddenfiles'    => false,
                      'cleardependencies' => false,
                      'clearcontents'     => true,
                      'clearchangelog'    => false,
                      );

    /**
     * @see    setOptions()
     * @access public
     * @since  1.0.0a1
     */
    function __construct()
    {
        parent::__construct();
        $config = &PEAR_Config::singleton();
        $this->setConfig($config);
    }

    /**
     * Add a pattern to include when generating the file list.
     *
     * If any include options are specified, all files that do not match the
     * inclusion patterns will be ignored
     *
     * Note that to match partial path entries, you must start with a *,
     * so to match "data/README" you need to use "*data/README"
     *
     * @param string|array $include file pattern to include
     * @param bool         $clear   (optional) if true, the include array will be reset
     *                        (useful for cloned packagefiles)
     *
     * @return void
     * @access public
     * @since  1.6.0a2
     */
    function addInclude($include, $clear = false)
    {
        if ($clear) {
            $this->_options['include'] = array();
        }

        if (is_array($include)) {
            foreach ($include as $fn) {
                if (is_string($fn)) {
                    $this->_options['include'][] = $fn;
                }
            }
            return;
        }
        $this->_options['include'][] = $include;
    }

    /**
     * Add an <ignore> tag to a <phprelease> tag
     *
     * @param string $path full path to filename to ignore
     *
     * @return void
     * @access public
     * @see    PEAR_PackageFile_v2_rw::addIgnore()
     * @since  1.6.0a3
     */
    function addIgnoreToRelease($path)
    {
        return parent::addIgnore($path);
    }

    /**
     * Add a pattern or patterns to ignore when generating the file list
     *
     * @param string|array $ignore file pattern to ignore
     * @param bool         $clear  (optional) if true, the include array will be reset
     *                             (useful for cloned packagefiles)
     *
     * @return void
     * @access public
     * @since  1.6.0a2
     */
    function addIgnore($ignore, $clear = false)
    {
        if ($clear) {
            $this->_options['ignore'] = array();
        }
        if (is_array($ignore)) {
            foreach ($ignore as $fn) {
                if (is_string($fn)) {
                    $this->_options['ignore'][] = $fn;
                }
            }
            return;
        }
        $this->_options['ignore'][] = $ignore;
    }

    /**
     * Set package.xml generation options
     *
     * The options array is indexed as follows:
     * <code>
     * $options = array('option_name' => <optionvalue>);
     * </code>
     *
     * The documentation below simplifies this description through
     * the use of option_name without quotes
     *
     * Configuration options:
     * - lang: lang controls the language in which error messages are
     *         displayed.  There are currently only English error messages,
     *         but any contributed will be added over time.<br>
     *         Possible values: en (default)
     * - packagefile: the name of the packagefile, defaults to package.xml
     * - pathtopackagefile: the path to an existing package file to read in,
     *                      if different from the packagedirectory
     * - packagedirectory: the path to the base directory of the package.  For
     *                     package PEAR_PackageFileManager, this path is
     *                     /path/to/pearcvs/pear/PEAR_PackageFileManager where
     *                     /path/to/pearcvs is a local path on your hard drive
     * - outputdirectory: the path in which to place the generated package.xml
     *                    by default, this is ignored, and the package.xml is
     *                    created in the packagedirectory
     * - filelistgenerator: the <filelist> section plugin which will be used.
     *                      In this release, there are two generator plugins,
     *                      file and cvs.  For details, see the docs for these
     *                      plugins
     * - usergeneratordir: For advanced users.  If you write your own filelist
     *                     generator plugin, use this option to tell
     *                     PEAR_PackageFileManager where to find the file that
     *                     contains it.  If the plugin is named foo, the class
     *                     must be named PEAR_PackageFileManager_Foo
     *                     no matter where it is located.  By default, the Foo
     *                     plugin is located in PEAR/PackageFileManager/Foo.php.
     *                     If you pass /path/to/foo in this option, setOptions
     *                     will look for PEAR_PackageFileManager_Foo in
     *                     /path/to/foo/Foo.php
     * - changelogoldtonew: True if the ChangeLog should list from oldest entry to
     *                      newest.  Set to false if you would like new entries first
     * - simpleoutput: True if the package.xml should be human-readable
     * - clearchangelog: True if change log should not be generated/updated
     * - addhiddenfiles: True if you wish to add hidden files/directories that begin with .
     *                   like .bashrc.  This is only used by the File generator.  The CVS
     *                   generator will use all files in CVS regardless of format
     *
     * package.xml simple options:
     * - baseinstalldir: The base directory to install this package in.  For
     *                   package PEAR_PackageFileManager, this is "PEAR", for
     *                   package PEAR, this is "/"
     * - changelognotes: notes for the changelog, this should be more detailed than
     *                   the release notes.  By default, PEAR_PackageFileManager uses
     *                   the notes option for the changelog as well
     *
     * <b>WARNING</b>: all complex options that require a file path are case-sensitive
     *
     * package.xml complex options:
     * - ignore: an array of filenames, directory names, or wildcard expressions specifying
     *           files to exclude entirely from the package.xml.  Wildcards are operating system
     *           wildcards * and ?.  file*foo.php will exclude filefoo.php, fileabrfoo.php and
     *           filewho_is_thisfoo.php.  file?foo.php will exclude fileafoo.php and will not
     *           exclude fileaafoo.php.  test/ will exclude all directories and subdirectories of
     *           ANY directory named test encountered in directory parsing.  *test* will exclude
     *           all files and directories that contain test in their name
     * - include: an array of filenames, directory names, or wildcard expressions specifying
     *            files to include in the listing.  All other files will be ignored.
     *            Wildcards are in the same format as ignore
     * - roles: this is an array mapping file extension to install role.  This
     *          specifies default behavior that can be overridden by the exceptions
     *          option and dir_roles option.  use {@link addRole()} to add a new
     *          role to the pre-existing array
     * - dir_roles: this is an array mapping directory name to install role.  All
     *              files in a directory whose name matches the directory will be
     *              given the install role specified.  Single files can be excluded
     *              from this using the exceptions option.  The directory should be
     *              a relative path from the baseinstalldir, or "/" for the baseinstalldir
     * - exceptions: specify file role for specific files.  This array maps all files
     *               matching the exact name of a file to a role as in "file.ext" => "role"
     * - globalreplacements: a list of replacements that should be performed on every single file.
     *                       The format is the same as replacements
     * - globalreplaceexceptions: a list of exact filenames that should not have global
     *                            replacements performed (useful for images and large files)
     *                            note that this is not exported to package.xml 1.0!!
     *
     * @param array   $options  (optional) list of generation options
     * @param boolean $internal (optional) private function call
     *
     * @see    PEAR_PackageFileManager_File
     * @see    PEAR_PackageFileManager_CVS
     * @return void|PEAR_Error
     * @throws PEAR_PACKAGEFILEMANAGER2_NOPKGDIR
     * @throws PEAR_PACKAGEFILEMANAGER2_PKGDIR_NOTREAL
     * @throws PEAR_PACKAGEFILEMANAGER2_PATHTOPKGDIR_NOTREAL
     * @throws PEAR_PACKAGEFILEMANAGER2_OUTPUTDIR_NOTREAL
     * @throws PEAR_PACKAGEFILEMANAGER2_NOBASEDIR
     * @throws PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND_ANYWHERE
     * @throws PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND
     * @access public
     * @since  1.0.0a1
     */
    function setOptions($options = array(), $internal = false)
    {
        if (!isset($options['packagedirectory']) || !$options['packagedirectory']) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_NOPKGDIR);
        }

        if (!file_exists($options['packagedirectory'])) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_PKGDIR_NOTREAL,
                $options['packagedirectory']);
        }

        $options['packagedirectory'] = str_replace(DIRECTORY_SEPARATOR,
                                                 '/',
                                                 realpath($options['packagedirectory']));
        if ($options['packagedirectory']{strlen($options['packagedirectory']) - 1} != '/') {
            $options['packagedirectory'] .= '/';
        }

        if (isset($options['pathtopackagefile']) && $options['pathtopackagefile']) {
            if (!file_exists($options['pathtopackagefile'])) {
                return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_PATHTOPKGDIR_NOTREAL,
                    $options['pathtopackagefile']);
            }
            $options['pathtopackagefile'] = str_replace(DIRECTORY_SEPARATOR,
                                                     '/',
                                                     realpath($options['pathtopackagefile']));
            if ($options['pathtopackagefile']{strlen($options['pathtopackagefile']) - 1} != '/') {
                $options['pathtopackagefile'] .= '/';
            }
        }

        if (isset($options['outputdirectory']) && $options['outputdirectory']) {
            if (!file_exists($options['outputdirectory'])) {
                return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_OUTPUTDIR_NOTREAL,
                    $options['outputdirectory']);
            }

            $options['outputdirectory'] = str_replace(DIRECTORY_SEPARATOR,
                                                     '/',
                                                     realpath($options['outputdirectory']));
            if ($options['outputdirectory']{strlen($options['outputdirectory']) - 1} != '/') {
                $options['outputdirectory'] .= '/';
            }
        }

        if (!isset($options['baseinstalldir']) || !$options['baseinstalldir']) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_NOBASEDIR);
        }

        $this->_options = array_merge($this->_options, $options);
        if (!isset($this->_options['roles']['*'])) {
            $this->_options['roles']['*'] = 'data';
        }

        $path = ($this->_options['pathtopackagefile'] ?
                    $this->_options['pathtopackagefile'] : $this->_options['packagedirectory']);
        $this->_options['filelistgenerator'] =
            ucfirst(strtolower($this->_options['filelistgenerator']));
        if (!$internal) {
            if (PEAR::isError($res = PEAR_PackageFileManager2::_getExistingPackageXML($path,
                  $this->_options['packagefile'], array('cleardependencies' => true)))) {
                return $res;
            }
            $this->_oldPackageFile = $res;
        }

        // file generator resource to load
        $resource = 'PEAR/PackageFileManager/' . ucfirst(strtolower($this->_options['filelistgenerator'])) . '.php';
        // file generator class name
        $className = substr($resource, 0, -4);
        $className = str_replace('/', '_', $className);

        if (class_exists($className)) {
            return;
        }

        // attempt to load the interface from the standard PEAR location
        if ($this->isIncludeable($resource)) {
            include_once $resource;
        } elseif (isset($this->_options['usergeneratordir'])) {
            // attempt to load from a user-specified directory
            if (is_dir(realpath($this->_options['usergeneratordir']))) {
                $this->_options['usergeneratordir'] =
                    str_replace(DIRECTORY_SEPARATOR,
                                '/',
                                realpath($this->_options['usergeneratordir']));
                if ($this->_options['usergeneratordir']{strlen($this->_options['usergeneratordir']) - 1} != '/') {
                    $this->_options['usergeneratordir'] .= '/';
                }
            } else {
                $this->_options['usergeneratordir'] = '////';
            }

            $generator = $this->_options['usergeneratordir'] .
                             ucfirst(strtolower($this->_options['filelistgenerator'])) . '.php';
            if (file_exists($generator) && is_readable($generator)) {
                include_once $generator;
            }

            if (!class_exists($className)) {
                return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_GENERATOR_NOTFOUND_ANYWHERE,
                    $className);
            }
        }
    }

    /**
     * Define a link between a subpackage and the parent package
     *
     * In many cases, a subpackage is developed in the same directory
     * as the parent package, and the files should be excluded from the package.xml
     * version 2.0.
     *
     * @param object  &$pm        PEAR_PackageFileManager2 object representing the subpackage's package.xml
     * @param boolean $dependency dependency type to add, use true for a package dependency,
     *                 false for a subpackage dependency
     * @param boolean $required   (optional) whether the dependency should be required or optional
     *
     * @return void|false
     * @access public
     * @since  1.0.0a1
     */
    function specifySubpackage(&$pm, $dependency = null, $required = false)
    {
        if (!$pm->getDate()) {
            $pm->setDate(date('Y-m-d'));
        }

        if (!$pm->validate(PEAR_VALIDATE_NORMAL)) {
            return false;
        }

        $this->_subpackages[] = &$pm;
        if ($dependency !== null) {
            $type = $required ? 'required' : 'optional';
            if ($pm->getChannel()) {
                if ($dependency) {
                    $this->addPackageDepWithChannel($type, $pm->getPackage(), $pm->getChannel(),
                        $pm->getVersion(), false, false, false, $pm->getProvidesExtension());
                } else {
                    $this->addSubPackageDepWithChannel($type, $pm->getPackage(), $pm->getChannel(),
                        $pm->getVersion(), false, false, false, $pm->getProvidesExtension());
                }
            } else {
                if ($dependency) {
                    $this->addPackageDepWithUri($type, $pm->getPackage(), $pm->getUri(),
                        $this->getProvidesExtension());
                } else {
                    $this->addSubpackageDepWithUri($type, $pm->getPackage(), $pm->getUri(),
                        $this->getProvidesExtension());
                }
            }
        }
    }

    /**
     * Convert a package xml 1.0 to 2.0 with user and default options
     *
     * @param string $packagefile name of package file
     * @param array  $options     (optional) list of generation options
     *
     * @return PEAR_PackageFileManager2|PEAR_Error
     * @static
     * @access public
     * @since  1.0.0a1
     */
    public static function &importFromPackageFile1($packagefile, $options = array())
    {
        $z   = &PEAR_Config::singleton();
        $pkg = new PEAR_PackageFile($z);
        $pf  = $pkg->fromPackageFile($packagefile, PEAR_VALIDATE_NORMAL);
        if (PEAR::isError($pf)) {
            return $pf;
        }

        if ($pf->getPackagexmlVersion() == '1.0') {
            $packagefile = &$pf;
        }

        $a = &PEAR_PackageFileManager2::importOptions($packagefile, $options);
        return $a;
    }

    /**
     * Import options from an existing package.xml
     *
     * @param string $packagefile name of package file
     * @param array  $options     (optional) list of generation options
     *
     * @return PEAR_PackageFileManager2|PEAR_Error
     * @static
     * @access public
     * @since  1.0.0a1
     */
    public static function &importOptions($packagefile, $options = array())
    {
        if (is_a($packagefile, 'PEAR_PackageFile_v1')) {
            $gen = &$packagefile->getDefaultGenerator();
            $res = $gen->toV2('PEAR_PackageFileManager2');
            if (PEAR::isError($res)) {
                return $res;
            }

            $res->setOld();
            if (isset($options['cleardependencies']) && $options['cleardependencies']) {
                $res->clearDeps();
            }

            if (!isset($options['clearcontents']) || $options['clearcontents']) {
                $res->clearContents();
            } else {
                $res->_importTasks($options);
            }
            $packagefile = $packagefile->getPackageFile();
        }

        if (!isset($res)) {
            $res = &PEAR_PackageFileManager2::_getExistingPackageXML(dirname($packagefile) .
                    DIRECTORY_SEPARATOR, basename($packagefile), $options);
            if (PEAR::isError($res)) {
                return $res;
            }
        }

        if (PEAR::isError($ret = $res->_importOptions($packagefile, $options))) {
            return $ret;
        }

        return $res;
    }

    /**
     * Import options from an existing package.xml 2.0
     *
     * @param string $packagefile name of package file
     * @param array  $options     list of generation options
     *
     * @return void|PEAR_Error
     * @access private
     * @since  1.0.0a1
     */
    function _importOptions($packagefile, $options)
    {
        $this->_options['packagedirectory']  = dirname($packagefile);
        $this->_options['pathtopackagefile'] = dirname($packagefile);
        $this->_options['baseinstalldir']    = '/';
        return $this->setOptions(array_merge($this->_options, $options), true);
    }

    /**
     * Get the existing options
     *
     * @param bool $withTasks (optional) Returns full options (=false)
     *                                   or without replacements (=true)
     *
     * @return array
     * @access public
     * @since  1.0.0a1
     */
    function getOptions($withTasks = false)
    {
        if ($withTasks === false) {
            return $this->_options;
        }
        $opt = $this->_options;
        unset($opt['replacements']);
        return $opt;
    }

    /**
     * Add an extension/role mapping to the role mapping option
     *
     * Roles influence both where a file is installed and how it is installed.
     * Files with role="data" are in a completely different directory hierarchy
     * from the program files of role="php"
     *
     * In PEAR 1.3b2, these roles are
     * - php (most common)
     * - data
     * - doc
     * - test
     * - script (gives the file an executable attribute)
     * - src
     *
     * @param string $extension file extension
     * @param string $role      file role
     *
     * @return void|PEAR_Error
     * @throws PEAR_PACKAGEFILEMANAGER2_INVALID_ROLE
     * @access public
     * @since  1.0.0a1
     */
    function addRole($extension, $role)
    {
        include_once 'PEAR/Installer/Role.php';
        $roles = PEAR_Installer_Role::getValidRoles($this->getPackageType());
        if (!in_array($role, $roles)) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_ROLE, implode($roles, ', '), $role);
        }
        $this->_options['roles'][$extension] = $role;
    }

    /**
     * Add a replacement option for all files
     *
     * This sets an install-time complex search-and-replace function
     * allowing the setting of platform-specific variables in all
     * installed files.
     *
     * if $type is php-const, then $to must be the name of a PHP Constant.
     * If $type is pear-config, then $to must be the name of a PEAR config
     * variable accessible through a {@link PEAR_Config::get()} method.  If
     * type is package-info, then $to must be the name of a section from
     * the package.xml file used to install this file.
     *
     * @param string $type variable type, either php-const, pear-config or package-info
     * @param string $from text to replace in the source file
     * @param string $to   variable name to use for replacement
     *
     * @return void|PEAR_Error
     * @throws PEAR_PACKAGEFILEMANAGER2_INVALID_REPLACETYPE
     * @access public
     * @since  1.0.0a1
     */
    function addGlobalReplacement($type, $from, $to)
    {
        include_once 'PEAR/Task/Replace/rw.php';
        if (!isset($this->_options['globalreplacements'])) {
            $this->_options['globalreplacements'] = array();
        }

        $l = null;
        $task = new PEAR_Task_Replace_rw($this, $this->_config, $l, '');
        $task->setInfo($from, $to, $type);
        if (is_array($res = $task->validate())) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_REPLACETYPE,
                implode(', ', $res[3]), $res[1] . ': ' . $res[2]);
        }

        $this->_options['globalreplacements'][] = $task;
    }

    /**
     * Add a replacement option for a file, or files matching the glob pattern
     *
     * This sets an install-time complex search-and-replace function
     * allowing the setting of platform-specific variables in an
     * installed file.
     *
     * if $type is php-const, then $to must be the name of a PHP Constant.
     * If $type is pear-config, then $to must be the name of a PEAR config
     * variable accessible through a {@link PEAR_Config::get()} method.  If
     * type is package-info, then $to must be the name of a section from
     * the package.xml file used to install this file.
     *
     * @param string $path relative path of file (relative to packagedirectory option)
     *                     glob patterns are allowed (eg. {Dir1,Dir2}/*.php)
     * @param string $type variable type, either php-const, pear-config or package-info
     * @param string $from text to replace in the source file
     * @param string $to   variable name to use for replacement
     *
     * @return void|PEAR_Error
     * @throws PEAR_PACKAGEFILEMANAGER2_INVALID_REPLACETYPE
     * @access public
     * @since  1.0.0a1
     */
    function addReplacement($path, $type, $from, $to)
    {
        if (!isset($this->_options['replacements'])) {
            $this->_options['replacements'] = array();
        }

        include_once 'PEAR/Task/Replace/rw.php';
        $l = null;
        $task = new PEAR_Task_Replace_rw($this, $this->_config, $l, '');
        $task->setInfo($from, $to, $type);
        if (is_array($res = $task->validate())) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_REPLACETYPE,
                implode(', ', $res[3]), $res[1] . ': ' . $res[2]);
        }

        $current_dir = getcwd();
        chdir($this->_options['packagedirectory']);
        $glob = defined('GLOB_BRACE') ? glob($path, GLOB_BRACE) : glob($path);
        chdir($current_dir);

        if (false !== $glob) {
            foreach ($glob as $pathItem) {
                $this->_options['replacements'][$pathItem][] = $task;
            }
        }
    }

    /**
     * Convert a file to windows line endings on installation
     *
     * @param string $path relative path of file (relative to packagedirectory option)
     *
     * @return void
     * @access public
     * @since  1.0.0a1
     */
    function addWindowsEol($path)
    {
        if (!isset($this->_options['replacements'])) {
            $this->_options['replacements'] = array();
        }
        include_once 'PEAR/Task/Windowseol/rw.php';
        $l = null;
        $task = new PEAR_Task_Windowseol_rw($this, $this->_config, $l, '');
        // we'll use this because it will still work
        $this->_options['replacements'][$path][] = $task;
    }

    /**
     * Convert a file to unix line endings on installation
     *
     * @param string $path relative path of file (relative to packagedirectory option)
     *
     * @return void
     * @access public
     * @since  1.0.0a1
     */
    function addUnixEol($path)
    {
        if (!isset($this->_options['replacements'])) {
            $this->_options['replacements'] = array();
        }
        include_once 'PEAR/Task/Unixeol/rw.php';
        $l = null;
        $task = new PEAR_Task_Unixeol_rw($this, $this->_config, $l, '');
        // we'll use this because it will still work
        $this->_options['replacements'][$path][] = $task;
    }

    /**
     * Get a post-installation task object for manipulation prior to adding it
     *
     * @param string $path relative path of file (relative to packagedirectory option)
     *
     * @return PEAR_Task_Postinstallscript_rw
     * @access public
     * @since  1.0.0a1
     */
    function &initPostinstallScript($path)
    {
        include_once 'PEAR/Task/Postinstallscript/rw.php';
        $options = array('name' => $path, 'role' => 'php');
        $task = new PEAR_Task_Postinstallscript_rw($this, $this->_config, $l, $options);
        return $task;
    }

    /**
     * Add post-installation script task to a post-install script.
     *
     * The script must have been created with {@link initPostinstallScript()} and
     * then populated using the API of PEAR_Task_Postinstallscript_rw.
     *
     * @param object $task PEAR_Task_Postinstallscript_rw
     * @param string $path relative path of file (relative to packagedirectory option)
     *
     * @return void|PEAR_Error
     * @throws PEAR_PACKAGEFILEMANAGER2_INVALID_POSTINSTALLSCRIPT
     * @access public
     * @since  1.0.0a1
     */
    function addPostinstallTask($task, $path)
    {
        if (!is_a($task, 'PEAR_Task_Postinstallscript')) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_POSTINSTALLSCRIPT,
                'Task passed in is not a PEAR_Task_Postinstallscript task');
        }

        // necessary for validation
        $this->addFile('', $path, array('role' => 'php', 'name' => $path));
        $this->setPackagefile($this->_options['packagedirectory'] .
            DIRECTORY_SEPARATOR . $this->_options['packagefile']);
        if (is_array($res = $task->validate())) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_POSTINSTALLSCRIPT,
                $res[1]);
        }

        if (!isset($this->_options['replacements'])) {
            $this->_options['replacements'] = array();
        }
        $this->_options['replacements'][$path][] = $task;
    }

    /**
     * Uses PEAR::PHP_CompatInfo package to detect dependencies (extensions, php version)
     *
     * @param array $options (optional) parser options for PHP_CompatInfo
     *
     * @return void|PEAR_Error
     * @throws PEAR_PACKAGEFILEMANAGER2_RUN_SETOPTIONS
     * @throws PEAR_PACKAGEFILEMANAGER2_NO_PHPCOMPATINFO
     * @access public
     * @since  1.0.0a1
     */
    function detectDependencies($options = array())
    {
        if (!$this->isIncludeable('PHP/CompatInfo.php')) {
            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_NO_PHPCOMPATINFO);
        }

        include_once 'PHP/CompatInfo.php';
        if (!is_array($options)) {
            $options = array();
        }
        $this->_detectDependencies = $options;
    }

    /**
     * Returns whether or not a file is in the include path.
     *
     * @param string $file path to filename
     *
     * @return boolean true if the file is in the include path, false otherwise
     * @access public
     * @since  1.0.0a1
     */
    function isIncludeable($file)
    {
        foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $path) {
            $p = $path . DIRECTORY_SEPARATOR . $file;
            if (file_exists($p) && is_readable($p)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Writes the package.xml file out with the newly created <release></release> tag
     *
     * ALWAYS use {@link debugPackageFile} to verify that output is correct before
     * overwriting your package.xml
     *
     * @param boolean $debuginterface null if no debugging, true if web interface, false if command-line
     *
     * @throws PEAR_PACKAGEFILEMANAGER2_INVALID_PACKAGE
     * @throws PEAR_PACKAGEFILEMANAGER2_CANTWRITE_PKGFILE
     * @throws PEAR_PACKAGEFILEMANAGER2_CANTCOPY_PKGFILE
     * @throws PEAR_PACKAGEFILEMANAGER2_CANTOPEN_TMPPKGFILE
     * @throws PEAR_PACKAGEFILEMANAGER2_DEST_UNWRITABLE
     * @return true|PEAR_Error
     * @access public
     * @since  1.0.0a1
     */
    function writePackageFile($debuginterface = null)
    {
        $warnings = $this->_stack->getErrors(true);
        $this->setDate(date('Y-m-d'));
        if (count($warnings)) {
            $nl = (isset($debuginterface) && $debuginterface ? '<br />' : "\n");
            foreach ($warnings as $errmsg) {
                echo 'WARNING: ' . $errmsg['message'] . $nl;
            }
        }

        if ($this->_options['simpleoutput']) {
            $state = PEAR_VALIDATE_NORMAL;
        } else {
            $state = PEAR_VALIDATE_PACKAGING;
        }

        $this->_getDependencies();
        if ($this->_options['clearchangelog']) {
            $this->clearChangeLog();
        } else {
            $this->_updateChangeLog();
        }

        $outputdir = ($this->_options['outputdirectory'] ?
                        $this->_options['outputdirectory'] : $this->_options['packagedirectory']);
        $this->setPackagefile($this->_options['packagedirectory'] . $this->_options['packagefile']);
        if (!$this->validate($state)) {
            $errors = $this->getValidationWarnings();
            $ret = '';
            $nl = (isset($debuginterface) && $debuginterface ? '<br />' : "\n");
            $haserror = false;
            foreach ($errors as $err) {
                if (!$haserror && $err['level'] == 'error') {
                    $haserror = true;
                }
                if (isset($debuginterface) && $debuginterface) {
                    $msg = htmlspecialchars($err['message']);
                } else {
                    $msg = $err['message'];
                }
                $ret .= ucfirst($err['level']) . ': ' . $msg . $nl;
            }
            if ($haserror) {
                return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_PACKAGE, $nl, $ret);
            }
        }

        $gen = &$this->getDefaultGenerator();
        $pfm = $gen->toXml($state);
        if (isset($debuginterface)) {
            if ($debuginterface) {
                echo '<pre>' . htmlentities($pfm) . '</pre>';
            } else {
                echo $pfm;
            }
            return true;
        }

        $file = $outputdir . $this->_options['packagefile'];
        if ((file_exists($file) && is_writable($file)) || @touch($file)) {
            if ($fp = @fopen($file . '.tmp', "w")) {
                $written = @fwrite($fp, $pfm);
                @fclose($fp);
                if ($written === false) {
                    return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_CANTWRITE_PKGFILE);
                }

                if (!@copy($file . '.tmp', $file)) {
                    return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_CANTCOPY_PKGFILE);
                }

                @unlink($file . '.tmp');
                return true;
            }

            return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_CANTOPEN_TMPPKGFILE,
                    $outputdir . $this->_options['packagefile'] . '.tmp');
        }

        return $this->raiseError(PEAR_PACKAGEFILEMANAGER2_DEST_UNWRITABLE, $outputdir);
    }

    /**
     * ALWAYS use this to test output before overwriting your package.xml!!
     *
     * This method instructs writePackageFile() to simply print the package.xml
     * to output, either command-line or web-friendly (this is automatic
     * based on the existence of $_SERVER['PATH_TRANSLATED']
     *
     * @uses   writePackageFile() calls with the debug parameter set based on
     *           whether it is called from the command-line or web interface
     * @return true|PEAR_Error
     * @access public
     * @since  1.0.0a1
     */
    function debugPackageFile()
    {
        $webinterface = (php_sapi_name() != 'cli');
        return $this->writePackageFile($webinterface);
    }

    /**
     * Store a warning on the warning stack
     *
     * @param integer $code error code
     * @param array   $info additional specific error info
     *
     * @return void
     * @access public
     * @since  1.0.0a1
     */
    function pushWarning($code, $info)
    {
        $this->_warningStack[] = array('code' => $code,
                                       'message' => $this->_getMessage($code, $info));
    }

    /**
     * Retrieve the list of warnings
     *
     * @return array
     * @access public
     * @since  1.0.0a1
     */
    function getWarnings()
    {
        $a = $this->_warningStack;
        $this->_warningStack = array();
        return $a;
    }

    /**
     * Retrieve an error message from a code
     *
     * @param integer $code error code
     * @param array   $info additional specific error info
     *
     * @return string Error message
     * @access private
     * @since  1.0.0a1
     */
    function _getMessage($code, $info)
    {
        $msg = $GLOBALS['_PEAR_PACKAGEFILEMANAGER2_ERRORS'][$this->_options['lang']][$code];
        foreach ($info as $name => $value) {
            $msg = str_replace('%' . $name . '%', $value, $msg);
        }
        return $msg;
    }

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
     * @access public
     * @since  1.0.0a1
     */
    function raiseError($code, $i1 = '', $i2 = '')
    {
        return PEAR::raiseError('PEAR_PackageFileManager2 Error: ' .
                    sprintf($GLOBALS['_PEAR_PACKAGEFILEMANAGER2_ERRORS'][$this->_options['lang']][$code],
                    $i1, $i2), $code);
    }

    /**
     * Generates file list contents of package.xml
     *
     * @uses _getDirTag()     generate the xml from the array
     * @uses _getSimpleDirTag generate the xml from the array for human reading
     * @return void|PEAR_Error
     * @access private
     * @since  1.0.0a1
     */
    function generateContents()
    {
        $this->addIgnore(array('package.xml', 'package2.xml'));
        $options = $this->_options;
        if (count($this->_subpackages)) {
            if (!is_array($options['ignore'])) {
                $options['ignore'] = array();
            }

            $subp = count($this->_subpackages);
            for ($i = 0; $i < $subp; $i++) {
                $save     = $this->_subpackages[$i]->getArray();
                $filelist = $this->_subpackages[$i]->getFileList();
                foreach ($filelist as $file => $atts) {
                    $options['ignore'][] = '*' . $file; // ignore all subpackage files
                }
                $this->_subpackages[$i]->fromArray($save);
            }
        }

        $generatorclass = 'PEAR_PackageFileManager_' . ucfirst(strtolower($this->_options['filelistgenerator']));
        $generator      = new $generatorclass($options);
        $this->clearContents($this->_options['baseinstalldir']);
        $this->_struc = $generator->getFileList();
        if ($this->_options['simpleoutput']) {
            return $this->_getSimpleDirTag($this->_struc);
        }

        return $this->_getDirTag($this->_struc);
    }

    /**
     * Recursively generate the <filelist> section's <dir> and <file> tags, but with
     * simple human-readable output
     *
     * @param array|PEAR_Error $struc   the sorted directory structure, or an error
     *                                  from filelist generation
     * @param false|string     $role    (optional) whether the parent directory has a role this should
     *                         inherit
     * @param string           $_curdir (optional) indentation level
     *
     * @return array|PEAR_Error
     * @access private
     * @since  1.0.0a1
     */
    function _getSimpleDirTag($struc, $role = false, $_curdir = '')
    {
        if (PEAR::isError($struc)) {
            return $struc;
        }

        extract($this->_options);
        $ret = array();
        foreach ($struc as $dir => $files) {
            if (false && $dir === '/') {
                // global directory role? overrides all exceptions except file exceptions
                if (isset($dir_roles['/'])) {
                    $role = $dir_roles['/'];
                }
                return $this->_getSimpleDirTag($struc[$dir], $role, '');
            }

            // directory
            if (!isset($files['file']) || is_array($files['file'])) {
                // contains only directories
                if (isset($dir_roles[$_curdir . $dir])) {
                    $myrole = $dir_roles[$_curdir . $dir];
                } else {
                    $myrole = $role;
                }
                $recurdir = ($_curdir == '') ? $dir . '/' : $_curdir . $dir . '/';
                if ($recurdir == '//') {
                    $recurdir = '';
                }
                $this->_getSimpleDirTag($files, $myrole, $recurdir);
            } else {
                // contains files
                $myrole = '';
                if (!$role) {
                    $myrole = false;
                    if (isset($exceptions[$files['path']])) {
                        $myrole = $exceptions[$files['path']];
                    } elseif (isset($roles[$files['ext']])) {
                        $myrole = $roles[$files['ext']];
                    } else {
                        $myrole = $roles['*'];
                    }
                } else {
                    $myrole = $role;
                    if (isset($exceptions[$files['path']])) {
                        $myrole = $exceptions[$files['path']];
                    }
                }

                $test = explode('/', $files['path']);
                foreach ($test as $subpath) {
                    if ($subpath == 'CVS') {
                        $this->pushWarning(PEAR_PACKAGEFILEMANAGER2_CVS_PACKAGED,
                            array('path' => $files['path']));
                    }
                }

                $atts = array('role' => $myrole);
                if (isset($installexceptions[$files['path']])) {
                    $atts['baseinstalldir'] = $installexceptions[$files['path']];
                }

                $diradd = dirname($files['path']);
                $this->addFile($diradd == '.' ? '/' : $diradd, $files['file'], $atts);
                if (isset($globalreplacements) &&
                      !in_array($files['path'], $globalreplaceexceptions, true)) {
                    foreach ($globalreplacements as $task) {
                        $this->addTaskToFile($files['path'], $task);
                    }
                }

                if (isset($replacements[$files['path']])) {
                    foreach ($replacements[$files['path']] as $task) {
                        $this->addTaskToFile($files['path'], $task);
                    }
                }
            }
        }

        return;
    }

    /**
     * Recursively generate the <filelist> section's <dir> and <file> tags
     *
     * @param array|PEAR_Error $struc   the sorted directory structure, or an error
     *                         from filelist generation
     * @param false|string     $role    (optional) whether the parent directory has a role this should
     *                         inherit
     * @param string           $_curdir (optional) indentation level
     *
     * @return array|PEAR_Error
     * @access private
     * @since  1.0.0a1
     */
    function _getDirTag($struc, $role = false, $_curdir = '')
    {
        if (PEAR::isError($struc)) {
            return $struc;
        }

        extract($this->_options);
        foreach ($struc as $dir => $files) {
            if ($dir === '/') {
                // global directory role? overrides all exceptions except file exceptions
                if (isset($dir_roles['/'])) {
                    $role = $dir_roles['/'];
                }
                return $this->_getDirTag($struc[$dir], $role, '');
            }

            // non-global directory
            if (!isset($files['file']) || is_array($files['file'])) {
                // contains only other directories
                $myrole = '';
                if (isset($dir_roles[$_curdir . $dir])) {
                    $myrole = $dir_roles[$_curdir . $dir];
                } elseif ($role) {
                    $myrole = $role;
                }
                $this->_getDirTag($files, $myrole, $_curdir . $dir . '/');
            } else {
                // contains files
                $myrole = '';
                if (!$role) {
                    $myrole = false;
                    if (isset($exceptions[$files['path']])) {
                        $myrole = $exceptions[$files['path']];
                    } elseif (isset($roles[$files['ext']])) {
                        $myrole = $roles[$files['ext']];
                    } else {
                        $myrole = $roles['*'];
                    }
                } else {
                    $myrole = $role;
                    if (isset($exceptions[$files['path']])) {
                        $myrole = $exceptions[$files['path']];
                    }
                }
                if (isset($installexceptions[$files['path']])) {
                    $bi = $installexceptions[$files['path']];
                } else {
                    $bi = $this->_options['baseinstalldir'];
                }
                $test = explode('/', $files['path']);
                foreach ($test as $subpath) {
                    if ($subpath == 'CVS') {
                        $this->pushWarning(PEAR_PACKAGEFILEMANAGER2_CVS_PACKAGED,
                            array('path' => $files['path']));
                    }
                }
                $atts =
                    array('role' => $myrole,
                          'baseinstalldir' => $bi,
                          );
                if (!isset($this->_options['simpleoutput']) || !$this->_options['simpleoutput']) {
                    $md5sum = @md5_file($this->_options['packagedirectory'] . $files['path']);
                    if (!empty($md5sum)) {
                        $atts['md5sum'] = $md5sum;
                    }
                }
                $diradd = dirname($files['path']);
                $this->addFile($diradd == '.' ? '/' : $diradd, $files['file'], $atts);
                if (isset($globalreplacements) &&
                      !in_array($files['path'], $globalreplaceexceptions, true)) {
                    foreach ($globalreplacements as $task) {
                        $this->addTaskToFile($files['path'], $task);
                    }
                }
                if (isset($replacements[$files['path']])) {
                    foreach ($replacements[$files['path']] as $task) {
                        $this->addTaskToFile($files['path'], $task);
                    }
                }
            }
        }

        return;
    }

    /**
     * @param array $files
     * @param array &$ret
     *
     * @return array
     * @access private
     * @since  1.0.0a1
     */
    function _traverseFileArray($files, &$ret)
    {
        foreach ($files as $file) {
            if (!isset($file['fullpath'])) {
                $this->_traverseFileArray($file, $ret);
            } else {
                $ret[] = $file['fullpath'];
            }
        }
    }

    /**
     * Retrieve the 'deps' option passed to the constructor
     *
     * @access private
     * @return void|PEAR_Error
     * @since  1.0.0a1
     */
    function _getDependencies()
    {
        if ($this->_detectDependencies) {
            $this->_traverseFileArray($this->_struc, $ret);
            $compatinfo  = new PHP_CompatInfo();
            $info        = $compatinfo->parseArray($ret, $this->_detectDependencies);
            $max_version = (empty($info['max_version'])) ? false : $info['max_version'];
            $ret = $this->setPhpDep($info['version'], $max_version);
            if (is_a($ret, 'PEAR_Error')) {
                return $ret;
            }

            foreach ($info['extensions'] as $ext) {
                $this->addExtensionDep('required', $ext);
            }
        }
        return;
    }

    /**
     * Creates a changelog entry with the current release
     * notes and dates, or overwrites a previous creation
     *
     * @return void
     * @access private
     * @since  1.0.0a1
     */
    function _updateChangeLog()
    {
        $changelog = $this->_oldPackageFile ? $this->_oldPackageFile->getChangelog() : false;
        $notes = $this->_options['changelognotes'];
        if (!$changelog) {
            $this->setChangelogEntry($this->getVersion(), $this->generateChangeLogEntry($notes));
            return;
        }

        if (!isset($changelog['release'][0])) {
            $changelog['release'] = array($changelog['release']);
        }
        $found = false;
        foreach ($changelog['release'] as $i => $centry) {
            $changelog['release'][$i]['notes'] = trim($changelog['release'][$i]['notes']);
            if ($centry['version']['release'] == $this->getVersion()) {
                $changelog['release'][$i] = $this->generateChangeLogEntry($notes);
                $found = true;
            }
        }
        if (!$found) {
            $changelog['release'][] = $this->generateChangeLogEntry($notes);
        }
        usort($changelog['release'], array($this, '_changelogsort'));
        $this->clearChangeLog();
        foreach ($changelog['release'] as $entry) {
            $this->setChangelogEntry($entry['version']['release'], $entry);
        }
    }

    /**
     * User-defined comparison function to sort changelog array
     *
     * @param array $a first array to compare items
     * @param array $b second array to compare items
     *
     * @return integer sort comparaison result (-1, 0, +1) of two elements $a and $b
     * @access private
     * @since  1.0.0a1
     */
    function _changelogsort($a, $b)
    {
        if (isset($a['date']) && isset($b['date'])) {
            if ($this->_options['changelogoldtonew']) {
                $c = strtotime($a['date']);
                $d = strtotime($b['date']);
            } else {
                $d = strtotime($a['date']);
                $c = strtotime($b['date']);
            }

            if ($c - $d > 0) {
                return 1;
            } elseif ($c - $d < 0) {
                return -1;
            }
        }

        if (isset($a['version']['release']) && isset($b['version']['release'])) {
            if ($this->_options['changelogoldtonew']) {
                $v1 = $a['version']['release'];
                $v2 = $b['version']['release'];
            } else {
                $v2 = $a['version']['release'];
                $v1 = $b['version']['release'];
            }

            return version_compare($v1, $v2);
        }

        return 0;
    }

    /**
     * @return void
     * @since  1.0.0a1
     */
    function setOld()
    {
        $this->_oldPackageFile = new PEAR_PackageFile_v2_rw();
        $this->_oldPackageFile->fromArray($this->getArray());
    }

    /**
     * Import tasks options and files roles (if exceptions)
     * from an existing package.xml
     *
     * @param array $options list of generation options
     *
     * @return void|PEAR_Error
     * @access private
     * @since  1.6.0b5
     */
    function _importTasks($options)
    {
        $filelist = $this->getFilelist(true);
        $vroles   = array_values($this->_options['roles']);

        foreach ($filelist as $file => $contents) {
            $atts = $contents['attribs'];
            unset($contents['attribs']);
            // check for tasks replacement, eol
            if (count($contents)) {
                foreach ($contents as $tag => $raw) {
                    $taskNs = $this->getTasksNs();
                    $task = str_replace("$taskNs:", '', $tag);
                    if ($task == 'replace') {
                        if (!isset($raw[0])) {
                            $raw = array($raw);
                        }
                        foreach ($raw as $attrs) {
                            $a = $attrs['attribs'];
                            $this->addReplacement($file, $a['type'], $a['from'], $a['to']);
                        }

                    } elseif ($task == 'windowseol') {
                        $this->addWindowsEol($file);

                    } elseif ($task == 'unixeol') {
                        $this->addUnixEol($file);

                    } elseif ($task == 'postinstallscript') {
                        $script = &$this->initPostinstallScript($file);
                        $raw = $this->_stripNamespace($raw);

                        foreach ($raw['paramgroup'] as $paramgroup) {
                            if (isset($paramgroup['instructions'])) {
                                $instructions = $paramgroup['instructions'];
                            } else {
                                $instructions = false;
                            }

                            if (isset($paramgroup['param'][0])) {
                                $params = $paramgroup['param'];
                            } else {
                                $params = array($paramgroup['param']);
                            }
                            $param = array();
                            foreach ($params as $p) {
                                $default = isset($p['default']) ? $p['default'] : null;
                                $param[] = $script->getParam($p['name'],
                                    $p['prompt'], $p['type'], $default);
                            }
                            $script->addParamGroup($paramgroup['id'], $param, $instructions);
                        }
                        $ret = $this->addPostinstallTask($script, $file);
                        if (PEAR::isError($ret)) {
                            return $ret;
                        }
                    }
                }
            }
            // check for role attribute
            if (isset($atts['role'])) {
                $myrole = $atts['role'];
                if (!in_array($myrole, $vroles)) {
                    $this->_options['exceptions'][$file] = $myrole;
                } else {
                    $inf = pathinfo($file);
                    if (isset($inf['extension'])) {
                        if (isset($this->_options['roles'][$inf['extension']])) {
                            $role = $this->_options['roles'][$inf['extension']];
                        } else {
                            $role = $this->_options['roles']['*'];
                        }
                        if ($role != $myrole) {
                            $this->_options['exceptions'][$file] = $myrole;
                        }
                    } else {
                        $this->_options['exceptions'][$file] = $myrole;
                    }
                }
            }
            // check for baseinstalldir attribute
            if (isset($options['baseinstalldir'])
                && isset($atts['baseinstalldir'])
                && $atts['baseinstalldir'] != $options['baseinstalldir']
            ) {
                $this->_options['installexceptions'][$file] = $atts['baseinstalldir'];
            }
        }
    }

    /**
     * Strip namespace from postinstallscript task array
     *
     * @param array $params tasks options
     *
     * @return array
     * @access private
     * @since  1.6.0b5
     */
    function _stripNamespace($params)
    {
        $newparams = array();
        foreach ($params as $i => $param) {
            if (is_array($param)) {
                $param = $this->_stripNamespace($param);
            }
            $newparams[str_replace($this->getTasksNs() . ':', '', $i)] = $param;
        }
        return $newparams;
    }

    /**
     * @param string $path        full path to package file
     * @param string $packagefile (optional) name of package file
     * @param array  $options     (optional) list of generation options
     *
     * @throws PEAR_PACKAGEFILEMANAGER2_INVALID_PACKAGE
     * @throws PEAR_PACKAGEFILEMANAGER2_PATH_DOESNT_EXIST
     * @return true|PEAR_Error
     * @uses   _generateNewPackageXML() if no package.xml is found, it
     *          calls this to create a new one
     * @access private
     * @static
     * @since  1.0.0a1
     */
    protected static function &_getExistingPackageXML($path, $packagefile = 'package.xml', $options = array())
    {
        if (is_string($path) && is_dir($path)) {
            $contents = false;
            if (file_exists($path . $packagefile)) {
                $contents = file_get_contents($path . $packagefile);
            }

            if (!$contents) {
                $a = PEAR_PackageFileManager2::_generateNewPackageXML();
                return $a;
            }

            include_once 'PEAR/PackageFile/Parser/v2.php';
            $pkg = new PEAR_PackageFile_Parser_v2();
            $z = &PEAR_Config::singleton();
            $pkg->setConfig($z);
            $pf = $pkg->parse($contents, $path . $packagefile, false,
                'PEAR_PackageFileManager2');
            if (PEAR::isError($pf)) {
                return $pf;
            }

            if (!$pf->validate(PEAR_VALIDATE_DOWNLOADING)) {
                $errors = '';
                foreach ($pf->getValidationWarnings() as $warning) {
                    $errors .= "\n" . ucfirst($warning['level']) . ': ' .
                        $warning['message'];
                }
                if (php_sapi_name() != 'cli') {
                    $errors = nl2br(htmlspecialchars($errors));
                }
                $a = $pf->raiseError(PEAR_PACKAGEFILEMANAGER2_INVALID_PACKAGE, $errors);
                return $a;
            }

            $pf->setOld();
            if (isset($options['cleardependencies']) && $options['cleardependencies']) {
                $pf->clearDeps();
            }

            if (!isset($options['clearcontents']) || $options['clearcontents']) {
                $pf->clearContents();
            } else {
                // merge options is required to use PEAR_PackageFileManager2::addPostinstallTask()
                $ret = $pf->_importOptions($packagefile, $options);
                if (PEAR::isError($ret)) {
                    return $ret;
                }
                $pf->_importTasks($options);
            }

            return $pf;
        }

        if (!is_string($path)) {
            $path = gettype($path);
        }
        include_once 'PEAR.php';
        $a = PEAR::raiseError('Path does not exist: ' . $path, PEAR_PACKAGEFILEMANAGER2_PATH_DOESNT_EXIST);
        return $a;
    }

    /**
     * Create the structure for a new package.xml
     *
     * @uses   $_packageXml emulates reading in a package.xml
     *           by using the package, summary and description
     *           options
     * @return PEAR_PackageFileManager2
     * @access private
     * @static
     * @since  1.0.0a1
     */
    protected static function &_generateNewPackageXML()
    {
        $pf = new PEAR_PackageFileManager2();
        $pf->_oldPackageFile = false;
        return $pf;
    }
}
