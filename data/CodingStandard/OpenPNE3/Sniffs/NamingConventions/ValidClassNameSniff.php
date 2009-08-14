<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_NamingConventions_ValidClassNameSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_NamingConventions_ValidClassNameSniff extends Squiz_Sniffs_Classes_ValidClassNameSniff
{
  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    if (false !== strpos($phpcsFile->getFilename(), 'model')
       || false !== strpos($phpcsFile->getFilename(), 'form') 
       || false !== strpos($phpcsFile->getFilename(), 'filter') 
    )
    {
      return null;
    }

    $tokens = $phpcsFile->getTokens();

    $className = $phpcsFile->findNext(T_STRING, $stackPtr);
    $name = trim($tokens[$className]['content']);

    // "op" prefix
    if (0 !== strpos($name, 'op'))
    {
      $error = ucfirst($tokens[$stackPtr]['content']).' name must begin with "op" prefix';
      $phpcsFile->addError($error, $stackPtr);
    }

    // "Interface" suffix
    if ($tokens[$stackPtr]['code'] === T_INTERFACE && !preg_match('/Interface$/', $name))
    {
      $error = ucfirst($tokens[$stackPtr]['content']).' name must end with "Interface"';
      $phpcsFile->addError($error, $stackPtr);
    }

    // stripped prefix
    if (0 === strpos($name, 'op'))
    {
      $name = substr($name, 2);
    }

    if (!PHP_CodeSniffer::isCamelCaps($name, true, true, false))
    {
      $error = ucfirst($tokens[$stackPtr]['content']).' name is not in camel caps format';
      $phpcsFile->addError($error, $stackPtr);
    }
  }
}
