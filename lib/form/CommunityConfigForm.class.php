<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * CommunityConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityConfigForm extends OpenPNEFormAutoGenerate
{
  protected
    $configSettings = array(),
    $category = '',
    $community,
    $isNew = false,
    $isAutoGenerate = true,
    $fieldName = 'config[%s]';

  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $this->setCommunity($this->getOption('community'));

    $this->setConfigSettings();

    if ($this->isAutoGenerate)
    {
      $this->generateConfigWidgets();
    }

    $this->widgetSchema->setNameFormat('community_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

  public function setCommunity(Community $community)
  {
    $this->community = $community;
  }

  public function generateConfigWidgets()
  {
    foreach ($this->configSettings as $key => $value)
    {
      $this->setConfigWidget($key);
    }
  }

  public function setConfigWidget($name)
  {
    $config = $this->configSettings[$name];
    $this->widgetSchema[sprintf($this->fieldName, $name)] = $this->generateWidget($config);
    $this->widgetSchema->setLabel(sprintf($this->fieldName, $name), $config['Caption']);
    $communityConfig = CommunityConfigPeer::retrieveByNameAndCommunityId($name, $this->community->getId());
    if ($communityConfig)
    {
      $this->setDefault(sprintf($this->fieldName, $name), $communityConfig->getValue());
    }
    $this->validatorSchema[sprintf($this->fieldName, $name)] = $this->generateValidator($config);
  }

  public function setConfigSettings($category = '')
  {
    $categories = sfConfig::get('openpne_community_category');
    $configs = sfConfig::get('openpne_community_config');

    if (!$category)
    {
      $this->configSettings = $configs;
      return true;
    }

    foreach ($categories[$category] as $value)
    {
      $this->configSettings[$value] = $configs[$value];
    }
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $key = $this->getUnformattedFieldName($key);
      $config = CommunityConfigPeer::retrieveByNameAndCommunityId($key, $this->community->getId());
      if (!$config)
      {
        $config = new CommunityConfig();
        $config->setCommunity($this->community);
        $config->setName($key);
      }
      $config->setValue($value);
      $config->save();
    }
  }

  public function getUnformattedFieldName($field)
  {
    $regexp = '/'.str_replace(array('%s'), array('(\w+)'), preg_quote($this->fieldName)).'/';
    $matches = array();
    preg_match($regexp, $field, $matches);
    array_shift($matches);

    return implode('', $matches);
  }
}
