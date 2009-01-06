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
