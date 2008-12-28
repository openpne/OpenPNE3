<?php

/**
 * opValidatorPCEmail validates PC emails.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorPCEmail extends sfValidatorEmail
{
  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    if (opToolkit::isMobileEmailAddress($clean))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    return $clean;
  }
}
