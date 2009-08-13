<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_Functions_OpeningFunctionBraceSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_Functions_OpeningFunctionBraceSniff extends Generic_Sniffs_Functions_OpeningFunctionBraceBsdAllmanSniff
{
  public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    // parent class is not a valid code so that cause Notice error
    // this method is for passing that
    @parent::process($phpcsFile, $stackPtr);
  }
}
