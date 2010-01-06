<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorHex validates hex.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorHex extends opValidatorString
{
  protected function doClean($value)
  {
    $zfValidator = new Zend_Validate_Hex();
    if (!$zfValidator->isValid($value))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    return $value;
  }
}
