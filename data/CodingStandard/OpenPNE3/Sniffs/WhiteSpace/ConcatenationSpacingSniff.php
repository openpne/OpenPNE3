<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_WhiteSpace_ConcatenationSpacingSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_WhiteSpace_ConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
{
  public function register()
  {
    return array(T_STRING_CONCAT);
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();
    $error = 'No space must be added before and after the "." operator.';

    if (' ' === $tokens[$stackPtr - 1]['content'][0])
    {
      $mayBeString = $phpcsFile->findPrevious(array(T_CONST, T_CONSTANT_ENCAPSED_STRING, T_VARIABLE, T_STRING), $stackPtr);

      if ($tokens[$stackPtr]['line'] === $tokens[$mayBeString]['line'])
      {
        $phpcsFile->addError($error, $stackPtr);
        return null;
      }

      $operator = $phpcsFile->findPrevious(T_EQUAL, $stackPtr, null, false, null, true);
      if ($tokens[$stackPtr]['column'] !== $tokens[$operator]['column'])
      {
        $phpcsFile->addError('"." operator must be aligned under the "=" operator.', $stackPtr);
      }
    }

    if (' ' === $tokens[$stackPtr + 1]['content'][0])
    {
      $phpcsFile->addError($error, $stackPtr);
      return null;
    }
  }
}
