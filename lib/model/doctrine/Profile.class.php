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
 /**
  * get options array
  *
  * @return array
  */
  public function getOptionsArray()
  {
    if ($this->isPreset())
    {
      return $this->getPresetOptionsArray();
    }

    $result = array();

    $options = $this->getProfileOption();

    foreach ($options as $option)
    {
      $result[$option->getId()] = $option->getValue();
    }

    return $result;
  }

 /**
  * get present options array
  *
  * @return array
  */
  public function getPresetOptionsArray()
  {
    $result = array();
    $config = $this->getPresetConfig();

    if (!empty($config['Choices']))
    {
      $result = array_combine($config['Choices'], $config['Choices']);
    }

    return $result;
  }

 /**
  * Checks if the profile is multiple select.
  *
  * @return boolean
  */
  public function isMultipleSelect()
  {
    return (bool)(('date' === $this->getFormType() && !$this->isPreset()) || 'checkbox' === $this->getFormType());
  }

 /**
  * Checks if the profile is single select.
  *
  * @return boolean
  */
  public function isSingleSelect()
  {
    return (bool)('radio' === $this->getFormType() || 'select' === $this->getFormType());
  }

 /**
  * Checks if the profile is preset.
  *
  * @return boolean
  */
  public function isPreset()
  {
    return (0 === strpos($this->getName(), 'op_preset_'));
  }

 /**
  * get raw preset name
  *
  * @return string
  */
  public function getRawPresetName()
  {
    if (!$this->isPreset())
    {
      return false;
    }

    $name = substr($this->getName(), strlen('op_preset_'));

    if ('region_select' === $this->getFormType()
        && 'string' !== $this->getValueType())
    {
      $name = 'region_'.$this->getValueType();
    }

    return $name;
  }

 /**
  * get preset config
  *
  * @return array
  */
  public function getPresetConfig()
  {
    $list = opToolkit::getPresetProfileList();
    if (!empty($list[$this->getRawPresetName()]))
    {
      return $list[$this->getRawPresetName()];
    }

    return array();
  }

 /**
  * get profile options
  *
  * @return Doctrine_Collection
  */
  public function getProfileOption()
  {
    return Doctrine::getTable('ProfileOption')->createQuery()
      ->where('profile_id = ?', $this->id)
      ->orderBy('sort_order')
      ->execute();
  }
}
