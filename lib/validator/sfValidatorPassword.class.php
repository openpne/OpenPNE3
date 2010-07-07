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
class sfValidatorPassword extends sfValidatorRegex
{
  /**
   * @see sfValidatorPassword
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', '/^[a-z0-9]+$/i');
    $this->addOption('max_length', 12);
    $this->addOption('min_length', 6);
    $this->setMessage('max_length', 'password is too long (%max_length% characters max).');
    $this->setMessage('min_length', 'password is too short (%min_length% characters min).');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $clean = md5(parent::doClean($value));
    return $clean;
  }
}
