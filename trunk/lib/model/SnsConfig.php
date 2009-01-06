<?php

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
