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

    $authMode = OpenPNEConfig::get(sfConfig::get('sf_app') . '_auth_mode');
    $containerClass = 'sfOpenPNEAuthContainer_' . $authMode;
    $this->authContainer = new $containerClass();

    $formClass = 'sfOpenPNEAuthForm_' . $authMode;
    $this->authForm = new $formClass();

    $this->initializeCredentials();
  }

  public function getAuthContainer()
  {
    return $this->authContainer;
  }

  public function getAuthForm()
  {
    return $this->authForm;
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
    $member_id = $this->getAuthContainer()->fetchData($form);

    if ($member_id) {
      $this->setAuthenticated(true);
      $this->setIsSNSMember(true);
      $this->setMemberId($member_id);
    }

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

    $this->setIsSNSMember(false);
    $this->setIsSNSRegisterFinish(false);

    if ($memberId && $isRegisterFinish) {
      $this->setIsSNSRegisterFinish(true);
    } elseif ($memberId) {
      $this->setIsSNSMember(true);
    }

    $this->setIsSNSRegisterBegin($this->getAuthContainer()->isRegisterBegin($memberId));
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
