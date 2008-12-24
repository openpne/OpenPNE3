<?php

/**
 * sfOpenPNESecurityUser will handle credential for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNESecurityUser extends sfBasicSecurityUser
{
  protected $authContainer = null;
  protected $authForm = null;

  /**
   * Initializes the current user.
   *
   * @see sfBasicSecurityUser
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);

    $request = sfContext::getInstance()->getRequest();
    $authMode = $request->getUrlParameter('authMode');

    $authModes = $this->getAuthModes();

    if (!$authMode || !in_array($authMode, $authModes))
    {
      $authMode = array_shift($authModes);
    }

    $containerClass = self::getAuthContainerClassName($authMode);
    $this->authContainer = new $containerClass();

    $formClass = self::getAuthFormClassName($authMode);
    $this->authForm = new $formClass();

    $this->initializeCredentials();
  }

  public function getAuthModes()
  {
    return OpenPNEConfig::get(sfConfig::get('sf_app') . '_auth_mode');
  }

  public function getAuthContainer()
  {
    return $this->authContainer;
  }

  public function getAuthForm()
  {
    return $this->authForm;
  }

  public function getAuthForms()
  {
    $result = array();

    $authModes = $this->getAuthModes();
    foreach ($authModes as $authMode) {
      $formClass = self::getAuthFormClassName($authMode);
      $result[] = new $formClass();
    }

    return $result;
  }

  public static function getAuthFormClassName($authMode)
  {
    return 'sfOpenPNEAuthForm_'.$authMode;
  }

  public static function getAuthContainerClassName($authMode)
  {
    return 'sfOpenPNEAuthContainer_'.$authMode;
  }

  public function getMemberId()
  {
    return $this->getAttribute('member_id', null, 'sfOpenPNESecurityUser');
  }

  public function setMemberId($memberId)
  {
    return $this->setAttribute('member_id', $memberId, 'sfOpenPNESecurityUser');
  }

  public function getMember()
  {
    return MemberPeer::retrieveByPk($this->getMemberId());
  }

  public function getRegisterEndAction()
  {
    return $this->getAuthContainer()->getRegisterEndAction();
  }

 /**
  * Login
  *
  * @param  sfForm $form
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function login($form)
  {
    if ($member = $form->getMember())
    {
      $memberId = $member->getId();
    }
    else  // deprecated
    {
      $memberId = $form->getValue('member_id');
    }

    if ($memberId)
    {
      $this->setMemberId($memberId);
      $this->setAuthenticated(true);
    }

    $this->initializeCredentials();

    return $this->isAuthenticated();
  }

 /**
  * Logout
  */
  public function logout()
  {
    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('sfOpenPNESecurityUser');
    $this->clearCredentials();
  }

 /**
  * Registers the current user with OpenPNE
  *
  * @param  sfForm $form
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function register($form = null)
  {
    $result = $this->getAuthContainer()->register($form);
    if ($result) {
      $this->setAuthenticated(true);
      $this->setAttribute('member_id', $result, 'sfOpenPNESecurityUser');
      return true;
    }

    return false;
  }

 /**
  * Initializes all credentials associated with this user.
  */
  public function initializeCredentials()
  {
    $memberId = $this->getMemberId();
    $isRegisterFinish = $this->getAuthContainer()->isRegisterFinish($memberId);
    $isRegisterBegin = $this->getAuthContainer()->isRegisterBegin($memberId);

    $this->setIsSNSMember(false);
    $this->setIsSNSRegisterFinish(false);

    if ($memberId && $isRegisterFinish)
    {
      $this->setIsSNSRegisterFinish(true);
    }
    elseif ($isRegisterBegin)
    {
      $this->setIsSNSRegisterBegin(true);
    }
    elseif ($memberId)
    {
      $this->setIsSNSMember(true);
    }
  }

  public function setIsSNSMember($isSNSMember)
  {
    if ($isSNSMember) {
      $this->setAuthenticated(true);
      $this->addCredential('SNSMember');
    } else {
      $this->removeCredential('SNSMember');
    }
  }

  public function setIsSNSRegisterBegin($isSNSRegisterBegin)
  {
    if ($this->hasCredential('SNSMember')) {
      $this->removeCredential('SNSRegisterBegin');
      return false;
    }

    if ($isSNSRegisterBegin) {
      $this->setAuthenticated(true);
      $this->addCredential('SNSRegisterBegin');
    } else {
      $this->removeCredential('SNSRegisterBegin');
    }
  }

  public function setIsSNSRegisterFinish($isSNSRegisterFinish)
  {
    if ($this->hasCredential('SNSMember')) {
      $this->removeCredential('SNSRegisterFinish');
      return false;
    }

    if ($isSNSRegisterFinish) {
      $this->setAuthenticated(true);
      $this->addCredential('SNSRegisterFinish');
    } else {
      $this->removeCredential('SNSRegisterFinish');
    }
  }
}
