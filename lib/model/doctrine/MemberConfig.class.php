<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberConfig extends BaseMemberConfig implements opAccessControlRecordInterface
{
  public function savePre()
  {
    $name = $this->getName();
    if (strrpos($name, '_pre') === false)
    {
      $name = $name.'_pre';
    }

    $pre = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name, $this->getMember()->getId());
    if (!$pre)
    {
      $pre = new MemberConfig();
      $pre->setName($name);
      $pre->setMember($this->Member);
    }

    $pre->setValue($this->getValue());
    return $pre->save();
  }

  public function getValue()
  {
    if ($this->_get('value_datetime'))
    {
      return $this->_get('value_datetime');
    }

    return $this->_get('value');
  }

  public function getFormType()
  {
    $setting = $this->getSetting();
    if (isset($setting['FormType']))
    {
      return $setting['FormType'];
    }

    return 'input';
  }

  public function preSave($event)
  {
    $modified = $this->getModified();
    if (isset($modified['value_datetime']))
    {
      $this->_set('value', $this->_get('value_datetime'));
    }
    elseif ('date' === $this->getFormType() && isset($modified['value']))
    {
      $this->_set('value_datetime', $this->_get('value'));
    }

    $this->_set('name_value_hash', $this->getTable()->generateNameValueHash($this->_get('name'), $this->_get('value')));
  }

  public function saveToken()
  {
    $baseName = $this->getName();
    if (strrpos($baseName, '_pre'))
    {
      $tokenName = str_replace('_pre', '_token', $baseName);
    }
    else
    {
      $tokenName = $baseName.'_token';
    }

    $pre = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($tokenName, $this->getMemberId());
    if (!$pre)
    {
      $pre = new MemberConfig();
      $pre->setName($tokenName);
      $pre->setMember($this->getMember());
    }
    else
    {
      $pre = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($tokenName, $this->getMemberId());
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
    $config = sfConfig::get('openpne_member_config');

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

  public function generateRoleId(Member $member)
  {
    if ($this->Member->id === $member->id)
    {
      return 'self';
    }

    return 'everyone';
  }
}
