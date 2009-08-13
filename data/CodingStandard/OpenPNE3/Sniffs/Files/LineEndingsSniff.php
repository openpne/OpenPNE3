<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Files_LineEndingsSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Files_LineEndingsSniff extends Generic_Sniffs_Files_LineEndingsSniff
{
  public function register()
  {
    return array(T_WHITESPACE);
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    if ("\r" === $tokens[$stackPtr]['content'])
    {
      $error = 'End of line character is invalid; expected "\n" but found "\r"';
      $phpcsFile->addError($error, $stackPtr);
    }

    if ("\r\n" === $tokens[$stackPtr]['content'])
    {
      $error = 'End of line character is invalid; expected "\n" but found "\r\n"';
      $phpcsFile->addError($error, $stackPtr);
    }
  }
}
