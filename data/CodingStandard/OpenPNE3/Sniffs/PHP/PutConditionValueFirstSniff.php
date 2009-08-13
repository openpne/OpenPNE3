<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_PHP_PutConditionValueFirstSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_PHP_PutConditionValueFirstSniff implements PHP_CodeSniffer_Sniff
{
  public function register()
  {
    return array(
      T_IS_EQUAL,
      T_IS_IDENTICAL,
      T_IS_NOT_EQUAL,
      T_IS_NOT_IDENTICAL,
    );
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $firstOp = null;
    $secondOp = null;

    while ($lastWhiteSpace = $phpcsFile->findPrevious(T_WHITESPACE, $stackPtr - 1, null, false, null, true))
    {
      if (T_WHITESPACE !== $tokens[$lastWhiteSpace - 1]['code'])
      {
        break;
      }
    }

    $firstOp = $lastWhiteSpace - 1;

    while ($lastWhiteSpace = $phpcsFile->findNext(T_WHITESPACE, $stackPtr + 1, null, false, null, true))
    {
      if (T_WHITESPACE !== $tokens[$lastWhiteSpace + 1]['code'])
      {
        break;
      }
    }

    $secondOp = $lastWhiteSpace + 1;

    // $a === $b
    if (T_VARIABLE === $tokens[$firstOp]['code'] && T_VARIABLE === $tokens[$secondOp]['code'])
    {
      return null;
    }

    // $a === ''
    if (T_VARIABLE === $tokens[$firstOp]['code'] && T_VARIABLE !== $tokens[$secondOp]['code'])
    {
      $error = 'Put the value first to compare with a variable.';
      $phpcsFile->addError($error, $stackPtr);
    }

    if (T_NULL === $tokens[$firstOp]['code'] || T_NULL === $tokens[$secondOp]['code'])
    {
      $error = 'To check if a variable is null or not, use the is_null() native PHP function.';
      $phpcsFile->addError($error, $stackPtr);
    }
  }
}
