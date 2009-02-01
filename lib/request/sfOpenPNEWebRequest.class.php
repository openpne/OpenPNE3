<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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
  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array(), $options = array())
  {
    parent::initialize($dispatcher, $parameters, $attributes, $options);

    require_once 'Net/UserAgent/Mobile.php';
  }

  public function getMobile()
  {
    if (!$this->userAgentMobileInstance)
    {
      $this->userAgentMobileInstance = Net_UserAgent_Mobile::factory();
      if ($this->userAgentMobileInstance instanceof Net_UserAgent_Mobile_Error)
      {
        $this->userAgentMobileInstance = new Net_UserAgent_Mobile_NonMobile('');
      }
    }

    return $this->userAgentMobileInstance;
  }

  public function isMobile()
  {
    if (opConfig::get('is_check_mobile_ip') && !$this->isMobileIPAddress())
    {
      return false;
    }

    return !($this->getMobile()->isNonMobile());
  }

  public function isMobileIPAddress()
  {
    $ipList = (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mobile_ip_address.yml'));

    require_once 'Net/IPv4.php';

    $result = false;
    foreach ($ipList as $mobileIp)
    {
      if (Net_IPv4::ipInNetwork($_SERVER['REMOTE_ADDR'], $mobileIp))
      {
        $result = true;
        break;
      }
    }

    return $result;
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

    // OpenPNE doesn't need to know a plain mobile UID
    return md5($uid);
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
    if (!$this->isMobile())
    {
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

  public function getCurrentQueryString()
  {
    return http_build_query($this->getGetParameters());
  }
}
