<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opUserAgentMobile
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class opMobileUserAgent
{
  protected static
    $instance = null,
    $mobile = null;

  protected function __construct()
  {
    require_once 'Net/UserAgent/Mobile.php';
    
    self::$mobile = Net_UserAgent_Mobile::factory();
    if (self::$mobile instanceof Net_UserAgent_Mobile_Error)
    {
      self::$mobile = new Net_UserAgent_Mobile_NonMobile('');
    }
  }

  public static function getInstance()
  {
    if (is_null(self::$mobile))
    {
      $className = __CLASS__;
      self::$instance = new $className();
    }

    return self::$instance;
  }

  public static function resetInstance()
  {
    self::$mobile = null;

    return self::getInstance();
  }

  public function getMobile()
  {
    return self::$mobile;
  }

  public function isCookie()
  {
    $mobile = $this->getMobile();

    if (($mobile->isDoCoMo() && '1.0' == $mobile->getBrowserVersion()) || ($mobile->isSoftBank() && !($mobile->isType3GC() || $mobile->isTypeW())))
    {
      return false;
    }
    return true;
  }

  protected function __clone()
  {
  }
}
