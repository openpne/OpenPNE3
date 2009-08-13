<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Files_FileExtensionSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Files_FileExtensionSniff  implements PHP_CodeSniffer_Sniff
{
  public function register()
  {
    return array(T_OPEN_TAG);
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $prevOpenTag = $phpcsFile->findPrevious(T_OPEN_TAG, ($stackPtr - 1));
    if ($prevOpenTag !== false)
    {
      return;
    }

    $fileName  = $phpcsFile->getFileName();
    $nextClass = $phpcsFile->findNext(array(T_CLASS, T_INTERFACE), $stackPtr);

    if (false !== strpos($fileName, '.class.php'))
    {
      if ($nextClass === false)
      {
        $error = 'No interface or class found in ".class.php" file; use ".php" extension instead';
        $phpcsFile->addError($error, $stackPtr);
      }
    }
    elseif (false !== strpos($fileName, '.php'))
    {
      if ($nextClass !== false && false === strpos($fileName, 'model'))
      {
        $error = 'Use ".class.php" extension for the class file';
        $phpcsFile->addError($error, $stackPtr);
      }
    }
  }
}
