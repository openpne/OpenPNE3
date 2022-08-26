<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorMultipleEmail validates Urls.
 *
 * @package    OpenPNE
 * @subpackage validator
 */
class opValidatorMultipleUrl extends sfValidatorUrl
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
  }

  public function clean($value)
  {
    $value = str_replace("\r\n", "\n", $value);
    $value = str_replace("\r", "\n", $value);
    $values = explode("\n", $value);
    foreach ($values as $v)
    {
      parent::clean($v);
    }

    return $value;
  }
}
