<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorSearchQueryString
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opValidatorSearchQueryString extends sfValidatorString
{
 /**
  * Configures the current validator.
  *
  * Available options:
  * 
  *   * demiliter_pattern
  *
  * @param array $options  An array of options
  * @param array $messages An array of messages
  *
  * @see sfValidatorString
  */
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('demiliter_pattern', '/[\s　]+/u');

    parent::configure($options, $messages);
  }

  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    return preg_split($this->getOption('demiliter_pattern'), $clean, -1, PREG_SPLIT_NO_EMPTY);
  }
}
