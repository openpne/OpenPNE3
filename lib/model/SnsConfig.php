<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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

  public function getConfig()
  {
    $name = $this->getName();
    if ($name && isset($this->snsConfigSettings[$name]))
    {
      return $this->snsConfigSettings[$name];
    }

    return false;
  }
}
