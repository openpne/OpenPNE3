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
  public function savePre()
  {
    $this->getMember()->clearMemberConfigs();

    $name = $this->getName();
    if (strrpos($name, '_pre') === false)
    {
      $name = $name.'_pre';
    }

    $pre = MemberConfigPeer::retrieveByNameAndMemberId($name, $this->getMemberId());
    if (!$pre)
    {
      $pre = new self();
      $pre->setName($name);
      $pre->setMember($this->getMember());
    }

    $pre->setValue($this->getValue());
    return $pre->save();
  }

  public function saveToken()
  {
    $this->getMember()->clearMemberConfigs();

    $baseName = $this->getName();
    if (strrpos($baseName, '_pre'))
    {
      $tokenName = str_replace('_pre', '_token', $baseName);
    }
    else
    {
      $tokenName = $baseName.'_token';
    }

    $pre = MemberConfigPeer::retrieveByNameAndMemberId($tokenName, $this->getMemberId());
    if (!$pre)
    {
      $pre = new self();
      $pre->setName($tokenName);
      $pre->setMember($this->getMember());
    }
    else
    {
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
