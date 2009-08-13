<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Commenting_InlineCommentSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Commenting_InlineCommentSniff extends PEAR_Sniffs_Commenting_InlineCommentSniff
{
  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();

    if (0 === strpos($tokens[$stackPtr]['content'], '//')
        && $tokens[$stackPtr]['content'][2] !== ' ')
    {
      $error = 'standard C++ comments should start with a space';
      $phpcsFile->addWarning($error, $stackPtr);
    }

    parent::process($phpcsFile, $stackPtr);
  }
}
