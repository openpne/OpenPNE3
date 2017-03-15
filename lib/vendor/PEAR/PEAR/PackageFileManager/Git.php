<?php
/**
 * The git list plugin generator for both PEAR_PackageFileManager,
 * and PEAR_PackageFileManager2 classes.
 *
 * PHP versions 5 and 7
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Armen Baghumian <armen@OpenSourceClub.org>
 * @license   New BSD, Revised
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     File available since Release 1.0.3
 */

require_once 'PEAR/PackageFileManager/File.php';

/**
 * Generate a file list from a Git workingcopy.
 *
 * Note that this will <b>NOT</b> work on a
 * repository, only on a Git workingcopy
 *
 * @category  PEAR
 * @package   PEAR_PackageFileManager_Plugins
 * @author    Armen Baghumian <armen@OpenSourceClub.org>
 * @license   New BSD, Revised
 * @version   Release: 1.0.4
 * @link      http://pear.php.net/package/PEAR_PackageFileManager_Plugins
 * @since     Class available since Release 1.0.3
 */
class PEAR_PackageFileManager_Git extends PEAR_PackageFileManager_File
{
    function getFileList()
    {
        $directory = $this->_options['packagedirectory'];
        $git       = $this->_findGitRootDir($directory);

        if ($git) {
            $content    = null;
            $content   .= file_exists($git.'.gitignore') AND file_get_contents($git.'.gitignore');
            $content   .= file_exists($git.'.git/info/exclude') AND file_get_contents($git.'.git/info/exclude');
            $content    = trim($content, "\n");
            $content    = explode("\n", $content);
            $gitignore  = array('.git/*', '.gitignore');
            $gitinclude = array();

            foreach ($content as $pattern) {
                if (preg_match('/^\s*#.*$/', $pattern) || preg_match('/^\s*$/', $pattern)) {
                    continue;
                }

                if (preg_match('/^\s*!(.*)$/', $pattern, $match)) {
                    $gitinclude[] = $match[1];

                } else {
                    $gitignore[] = $pattern;
                }
            }

            $this->_options['ignore'] = is_array($this->_options['ignore']) ? array_merge($gitignore, $this->_options['ignore']) : $gitignore;
        }

        $fileslist = parent::getFileList();

        if (is_array($fileslist) && isset($gitignore) && count($gitinclude)) {
            // in gitignore you can ignore whole directory then include specified subdirectories
            // or files with "!" modifier at the begining of patterns.
            // we have to generate files list and merge it with the main result

            $this->_options['ignore']  = array();
            $this->_options['include'] = array();

            foreach ($gitinclude as $entry) {
                // make sure that entry exists in current include paths if not just ignore it.
                if ($this->_checkIgnore($entry, $git.$entry, 0)) {
                    continue;
                }

                $this->_options['include'][] = $entry;
            }

            if (count($this->_options['include'])) {
                $fileslist = array_merge_recursive($fileslist, parent::getFileList());
            }
        }

        return $fileslist;
    }

    function _findGitRootDir($directory)
    {
        $directory = realpath($directory);

        if (!file_exists($directory.DIRECTORY_SEPARATOR.'.git') && $directory != DIRECTORY_SEPARATOR) {
            $directory = realpath($directory.DIRECTORY_SEPARATOR.'..');
            return $this->_findGitRootDir($directory);
        }

        if (file_exists($directory.DIRECTORY_SEPARATOR.'.git')) {
            return $directory.DIRECTORY_SEPARATOR;
        }

        return False;
    }
}
