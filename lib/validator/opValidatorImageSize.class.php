<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opValidatorImageSize validates a image size
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class opValidatorImageSize extends opValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('ltrim', true);
    $this->setOption('rtrim', true);
  }

  protected function doClean($value)
  {
    $value = parent::doClean($value);
    if ($this->isEmpty($value))
    {
      return $this->getEmptyValue();
    }

    if (!in_array($value, sfImageHandler::getAllowedSize()))
    {
      throw new sfValidatorError($this, 'Not allowed');
    }

    return $value;
  }
}
