<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_WhiteSpace_ReturnSpacingSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_WhiteSpace_ReturnSpacingSniff implements PHP_CodeSniffer_Sniff
{
  public function register()
  {
    return array(T_RETURN);
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();
    $error = 'Return statements should have a blank line prior to it.';

    $prevLineToken = null;
    for ($i = $stackPtr; $i > 0; $i--)
    {
      if (false === strpos($tokens[$i]['content'], "\n"))
      {
        continue;
      }
      else
      {
        $prevLineToken = $i;
        break;
      }
    }

    if (is_null($prevLineToken))
    {
      $phpcsFile->addWarning($error, $stackPtr);

      return null;
    }

    $prevNonWhiteSpace = null;
    for ($i = $prevLineToken; $i > 0; $i--)
    {
      if (T_WHITESPACE === $tokens[$i]['code'])
      {
        continue;
      }
      else
      {
        $prevNonWhiteSpace = $i;
        break;
      }
    }

    if (!is_null($prevNonWhiteSpace))
    {
      $prevLine = $tokens[$prevLineToken]['line'];
      $prevNonWhiteSpaceLine = $tokens[$prevNonWhiteSpace]['line'];

      if ($prevLine === $prevNonWhiteSpaceLine)
      {
        $phpcsFile->addWarning($error, $stackPtr);
      }
    }
  }
}
