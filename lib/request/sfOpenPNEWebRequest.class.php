<?php

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
    $this->userAgentMobileInstance = Net_UserAgent_Mobile::factory();
  }

  public function isMobile()
  {
    return !($this->userAgentMobileInstance->isNonMobile());
  }
}
