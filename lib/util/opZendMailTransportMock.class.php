<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opZendMailTransportMock
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class opZendMailTransportMock extends Zend_Mail_Transport_Abstract
{
  public $EOL = "\n";

  public function _sendMail()
  {
  }
}
