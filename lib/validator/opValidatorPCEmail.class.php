<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
