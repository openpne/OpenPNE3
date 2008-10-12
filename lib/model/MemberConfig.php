<?php

/**
 * Subclass for representing a row from the 'member_config' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberConfig extends BaseMemberConfig
{
  var $memberConfigSettings = array();

  public function __construct()
  {
    $config = OpenPNEConfig::loadConfigYaml('member');

    if (array_key_exists('all', $config)) {
      $this->memberConfigSettings += $config['all'];
    }

    if (array_key_exists(sfConfig::get('sf_app'), $config)) {
      $this->memberConfigSettings += $config[sfConfig::get('sf_app')];
    }
  }

  public function savePre()
  {
    $pre = new self();
    $pre->setName($this->getName().'_pre');
    $pre->setValue($this->getValue());
    $pre->setMember($this->getMember());
    return $pre->save();
  }

  public function saveToken()
  {
    $baseName = $this->getName();
    if (strrpos($baseName, '_pre')) {
      $tokenName = str_replace('_pre', '_token', $baseName);
    } else {
      $tokenName = $baseName.'_token';
    }

    if ($this->isNew()) {
      $pre = new self();
      $pre->setName($tokenName);
      $pre->setMember($this->getMember());
    } else {
      $pre = MemberConfigPeer::retrieveByNameAndMemberId($tokenName, $this->getMemberId());
    }

    $pre->setValue($this->createHash());
    return $pre->save();
  }

  private function createHash()
  {
    return md5(uniqid(mt_rand(), true));
  }

  public function getSetting()
  {
    $name = $this->getName();
    if (!$name)
    {
      return array();
    }

    if (empty($config[$this->getName()]))
    {
      return array();
    }

    return $config[$this->getName()];
  }
}
