<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Functions_FunctionDeclarationBeforeArgumentSpacingSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Functions_FunctionDeclarationBeforeArgumentSpacingSniff implements PHP_CodeSniffer_Sniff
{
  public function register()
  {
    return array(T_FUNCTION);
  }

  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    $bracket = $phpcsFile->findNext(T_OPEN_PARENTHESIS, $stackPtr);
    $whitespace = $phpcsFile->findPrevious(T_WHITESPACE, $bracket);

    if ($bracket - $whitespace == 1)
    {
      $error = 'There are spaces between the function name and the opening parenthesis for the arguments.';
      $phpcsFile->addError($error, $stackPtr);
    }
  }
}
