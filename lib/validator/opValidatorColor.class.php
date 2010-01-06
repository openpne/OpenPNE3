<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorColor validates hex.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorColor extends opValidatorHex
{
  protected function doClean($value)
  {
    $value = str_replace('#', '', $value);

    if (!in_array(strlen($value), array(3, 6)))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    $clean = parent::doClean($value);

    return '#'.$clean;
  }
}
