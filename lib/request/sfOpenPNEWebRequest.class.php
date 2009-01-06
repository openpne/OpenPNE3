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
 * sfOpenPNEWebRequest class manages web requests.
 *
 * @package    OpenPNE
 * @subpackage request
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEWebRequest extends sfWebRequest
{
  protected $userAgentMobileInstance = null;

 /**
  * @see sfWebRequest
  */
  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array())
  {
    parent::initialize($dispatcher, $parameters, $attributes);

    require_once 'Net/UserAgent/Mobile.php';
  }

  public function getMobile()
  {
    if (!$this->userAgentMobileInstance) {
      $this->userAgentMobileInstance = Net_UserAgent_Mobile::factory();
    }

    return $this->userAgentMobileInstance;
  }

  public function isMobile()
  {
    return !($this->getMobile()->isNonMobile());
  }

 /**
  * Returns the mobile UID.
  *
  * @return string  mobile UID
  */
  public function getMobileUID()
  {
    if (!$this->isMobile()) {
      return false;
    }

    $uid = $this->getMobile()->getUID();
    if (!$uid)
    {
      if ($this->getMobile()->isSoftBank())
      {
        $uid = $this->getMobile()->getSerialNumber();
      }
      elseif ($this->getMobile()->isDoCoMo())
      {
        $uid = $this->getMobile()->getCardID();
      }
    }

    return $uid;
  }

 /**
  * Checks whether the mobile UID is a valid or not.
  *
  * This method consideres the older versions of OpenPNE(-2.14).
  *
  * @params  string $hashedUid
  *
  * @return  bool
  */
  public function isValidMobileUID($hashedUid)
  {
    if (!$this->isMobile()) {
      return false;
    }

    if ($hashedUid === md5($this->getMobile()->getUID()))
    {
      return true;
    }

    if ($this->getMobile()->isSoftBank())
    {
      return ($hashedUid === md5($this->getMobile()->getSerialNumber()));
    }

    if ($this->getMobile()->isDoCoMo())
    {
      return ($hashedUid === md5($this->getMobile()->getCardID()));
    }

    return false;
  }

  public function isCookie()
  {
    if ($this->getMobile()->isDoCoMo())
    {
      return false;
    }
    elseif ($this->getMobile()->isSoftBank())
    {
      if (!$this->getMobile()->isType3GC() && !$this->getMobile()->isTypeW())
      {
        return false;
      }
    }

    return true;
  }
}
