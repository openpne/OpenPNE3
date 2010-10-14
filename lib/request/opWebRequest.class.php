<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opWebRequest class manages web requests.
 *
 * @package    OpenPNE
 * @subpackage request
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opWebRequest extends sfWebRequest
{
  const SB_GW_COOKIE_NAME = 'is_sb_gw';
  const SB_GW_BASE_URL = 'https://secure.softbank.ne.jp/';
  const MOBILE_UID_COOKIE_NAME = 'op_mobile_uid';

  protected 
    $userAgentMobileInstance = null;

  private
    $outsideEncoding = null;

 /**
  * @see sfWebRequest
  */
  public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array(), $options = array())
  {
    parent::initialize($dispatcher, $parameters, $attributes, $options);

    $this->parameterHolder = new opParameterHolder();
    $this->attributeHolder = new opParameterHolder();

    $this->parameterHolder->add($parameters);
    $this->attributeHolder->add($attributes);

    // POST parameters
    $this->getParameters = get_magic_quotes_gpc() ? sfToolkit::stripslashesDeep($_GET) : $_GET;
    $this->parameterHolder->add($this->getParameters);

    // POST parameters
    $this->postParameters = get_magic_quotes_gpc() ? sfToolkit::stripslashesDeep($_POST) : $_POST;
    $this->parameterHolder->add($this->postParameters);

    if ($this->isMethod(sfWebRequest::POST))
    {
      $this->parameterHolder->remove('sf_method');
    }

    // additional parameters
    $this->requestParameters = $this->parseRequestParameters();
    $this->parameterHolder->add($this->requestParameters);

    $this->fixParameters();
  }

 /**
  * @see sfWebRequest
  */
  public function checkCSRFProtection()
  {
    try
    {
      parent::checkCSRFProtection();
    }
    catch (sfValidatorErrorSchema $e)
    {
      // retry checking for using sfForm (just for BC)
      $form = new sfForm();
      $form->bind($form->isCSRFProtected() ? array($form->getCSRFFieldName() => $this->getParameter($form->getCSRFFieldName())) : array());

      if (!$form->isValid())
      {
        throw $form->getErrorSchema();
      }
    }
  }

  public function getHost()
  {
    $result = parent::getHost();
    if (!$result)
    {
      $result = parse_url(sfConfig::get('op_base_url'), PHP_URL_HOST);
    }

    return $result;
  }

  public function getMobile()
  {
    return opMobileUserAgent::getInstance()->getMobile();
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
    require_once 'Net/IPv4.php';

    $ipList = (array)include(sfContext::getInstance()->getConfigCache()->checkConfig('config/mobile_ip_address.yml'));
    $carrier = strtolower($this->getMobile()->getCarrierLongName());

    $list = array();
    if (isset($ipList[$carrier]))
    {
      $list = $ipList[$carrier];
    }

    $result = false;
    foreach ($list as $mobileIp)
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
    if (!$this->isMobile())
    {
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
    if ($uid)
    {
      return md5($uid);
    }

    return false;
  }

  public function getMobileUidCookie()
  {
    return $this->getCookie(self::MOBILE_UID_COOKIE_NAME);
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
    return opMobileUserAgent::getInstance()->isCookie();
  }

  public function getCurrentQueryString()
  {
    return http_build_query($this->convertEncodingForOutputCallback($this->getGetParameters()));
  }

  public function getParameter($name, $default = null, $isStripNullbyte = true)
  {
    return $this->parameterHolder->get($name, $default, $isStripNullbyte);
  }

  public function getGetParameters($isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      return opToolkit::stripNullByteDeep(parent::getGetParameters());
    }
    else
    {
      return parent::getGetParameters();
    }
  }

  public function getPostParameters($isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      return opToolkit::stripNullByteDeep(parent::getPostParameters());
    }
    else
    {
      return parent::getPostParameters();
    }
  }

  public function getGetParameter($name, $default = null, $isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      return opToolkit::stripNullByteDeep(parent::getGetParameter($name, $default));
    }
    else
    {
      return parent::getGetParameter($name, $default);
    }
  }

  public function getPostParameter($name, $default = null, $isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      return opToolkit::stripNullByteDeep(parent::getPostParameter($name, $default));
    }
    else
    {
      return parent::getPostParameters($name, $default);
    }
  }

  public function getUrlParameter($name, $default = null, $isStripNullbyte = true)
  {
    if ($isStripNullbyte)
    {
      return opToolkit::stripNullByteDeep(parent::getUrlParameter($name, $default));
    }
    else
    {
      return parent::getUrlParameter($name, $default);
    }
  }

  public function getCurrentUri($isAbsolute = false)
  {
    $uri = $this->getUri();
    if (!$isAbsolute)
    {
      $uri = str_replace($this->getUriPrefix(), '', $uri);
    }

    return $uri;
  }

  public function convertEncodingForInput($from_encoding)
  {
    $this->outsideEncoding = $from_encoding;
    $parameter_holder = sfContext::getInstance()->getRequest()->getParameterHolder();

    foreach ($parameter_holder->getAll(false) as $key => $value)
    {
      $parameter_holder->set($key, $this->convertEncodingForInputCallback($value));
    }

    foreach (array('getParameters', 'postParameters', 'requestParameters') as $parameters)
    {
      foreach ($this->$parameters as $key => $value)
      {
        if (0 !== stripos($key, '_sf_'))
        {
          $this->{$parameters}[$key] = $this->convertEncodingForInputCallback($value);
        }
      }
    }
  }

  private function convertEncodingForInputCallback($value)
  {
    if (is_array($value))
    {
      return array_map(array($this, 'convertEncodingForInputCallback'), $value);
    }

    return mb_convert_encoding($value, 'UTF-8', $this->outsideEncoding);
  }

  private function convertEncodingForOutputCallback($value)
  {
    if (is_array($value))
    {
      return array_map(array($this, 'convertEncodingForOutputCallback'), $value);
    }

    return mb_convert_encoding($value, $this->outsideEncoding, 'UTF-8');
  }

  public function needToRedirectToSoftBankGateway()
  {
    if (
      !$this->getMobile()->isSoftBank()  // The gateway is usable only for SoftBank mobile phone
      || !sfConfig::get('op_use_ssl', false)  // OpenPNE doesn't consider SSL
      || !$this->isSecure()  // Non-SSL connection is already passed the gateway
      || $this->getMobile()->getUID() // This UID is granted by the gateway
      || $this->getCookie(self::SB_GW_COOKIE_NAME)  // Redirecting has already done
    )
    {
      return false;
    }

    return true;
  }

  public function redirectToSoftBankGateway()
  {
    $baseUrl = sfConfig::get('op_ssl_base_url');
    sfContext::getInstance()->getResponse()->setCookie(self::SB_GW_COOKIE_NAME, '1');

    $url = self::SB_GW_BASE_URL
         .str_replace(array('https://', 'http://'), '', $baseUrl[sfConfig::get('sf_app')])
         .$this->getCurrentUri();

    sfContext::getInstance()->getController()->redirect($url);
  }
}
