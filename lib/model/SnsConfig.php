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
 * Subclass for representing a row from the 'sns_config' table.
 *
 * 
 *
 * @package lib.model
 */ 
class SnsConfig extends BaseSnsConfig
{
  var $snsConfigSettings = array();

  public function __construct()
  {
    $this->snsConfigSettings = sfConfig::get('openpne_sns_config');
  }

  public function getValue()
  {
    $value = parent::getValue();

    $config = $this->getConfig();
    if ($config && 'selectMany' === $config['type'])
    {
      $value = unserialize($value);
    }

    return $value;
  }

  public function setValue($v)
  {
    $config = $this->getConfig();

    if ($config && 'selectMany' === $config['type'])
    {
      $v = serialize($v);
    }

    parent::setValue($v);
  }

  public function getConfig()
  {
    $name = $this->getName();
    if ($name && isset($this->snsConfigSettings[$name])) {
      return $this->snsConfigSettings[$name];
    }

    return false;
  }
}
