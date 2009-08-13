<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_NamingConventions_ValidClassPropertySniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_NamingConventions_ValidClassPropertySniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{
  protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $name = $tokens[$stackPtr]['content'];
    if (!PHP_CodeSniffer::isCamelCaps(substr($name, 1), false, true, false))
    {
      $error = $name.' name is not in camel caps format';
      $phpcsFile->addError($error, $stackPtr);
    }
  }

  protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    return null;
  }

  protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    return null;
  }
}
