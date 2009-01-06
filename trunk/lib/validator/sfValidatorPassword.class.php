<?php

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
