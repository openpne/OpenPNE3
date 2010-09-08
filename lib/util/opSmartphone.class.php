<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSmartphone
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     tozuka <tozuka@tejimaya.com>
 */
class opSmartphone
{
  protected static
    $instance = null,
    $user_agent = null;

  protected function __construct()
  {
    if (isset($_SERVER['HTTP_USER_AGENT']))
    {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
    }
    else
    {
      $user_agent = "";
    }
  }

  public static function getInstance()
  {
    if (is_null(self::$user_agent))
    {
      $className = __CLASS__;
      self::$instance = new $className();
    }

    return self::$instance;
  }

  public function isSmartphone()
  {
    if ($this->isIPhone() || $this->isAndroid())
    {
      return true;
    }

    return false;
  }

  public function isIPhone()
  {
    if (strpos(self::$user_agent, 'iPhone') !== false)
    {
      return true;
    }

    return false;
  }

  public function isAndroid()
  {
    if (strpos(self::$user_agent, 'Android') !== false)
    {
      return true;
    }

    return false;
  }

  public function isCookie()
  {
    return $this->isSmartphone();
  }

  protected function __clone()
  {
  }
}
