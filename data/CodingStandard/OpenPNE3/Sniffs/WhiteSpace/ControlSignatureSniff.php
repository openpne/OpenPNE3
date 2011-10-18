<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE3_Sniffs_WhiteSpace_ControlSignatureSniff
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class OpenPNE3_Sniffs_WhiteSpace_ControlSignatureSniff extends PHP_CodeSniffer_Standards_AbstractPatternSniff
{
  protected function getPatterns()
  {
    return array(
       'tryEOL *{EOL...}EOL *catch (...)EOL *{EOL',
       'doEOL *{EOL...}EOL *while (...);EOL',
       'while (...)EOL *{EOL',
       'for (...)EOL *{EOL',
       'if (...)EOL *{EOL',
       'foreach (...)EOL *{EOL',
       '} *EOLelseif (...)EOL *{EOL',
       '} *EOLelseEOL *{EOL',
    );
  }
}
