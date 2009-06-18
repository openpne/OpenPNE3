<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Profile extends BaseProfile
{
  public function getOptionsArray()
  {
    $result = array();

    $options = $this->getProfileOption();

    foreach ($options as $option)
    {
      $result[$option->getId()] = $option->getValue();
    }

    return $result;
  }

  public function isMultipleSelect()
  {
    return (bool)('date' === $this->getFormType() || 'checkbox' === $this->getFormType());
  }

  public function isSingleSelect()
  {
    return (bool)('radio' === $this->getFormType() || 'select' === $this->getFormType());
  }
}
