<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfValidatorPassword validates a password. It also converts the input value to a .
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfValidatorPassword extends sfValidatorString
{
  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = md5(parent::doClean($value));
    return $clean;
  }
}
