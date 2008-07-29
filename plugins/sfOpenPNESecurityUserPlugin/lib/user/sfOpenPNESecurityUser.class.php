<?php

/**
 * sfOpenPNESecurityUser will handle credential for OpenPNE.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNESecurityUser extends sfBasicSecurityUser
{
  protected $authContainer = null;
  protected $authForm = null;

  /**
   * Initializes this sfOpenPNESecurityUser.
   *
   * @see sfBasicSecurityUser
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);

    $authMode = sfConfig::get('app_openpne_auth_mode');
    if (!$authMode) {
        $authMode = 'LoginID';
    }

    $containerClass = 'sfOpenPNEAuthContainer_' . $authMode;
    $this->authContainer = new $containerClass();

    $formClass = 'sfOpenPNEAuthForm_' . $authMode;
    $this->authForm = new $formClass();

    $this->initializeCredential();
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

  public function getMember()
  {
    return MemberPeer::retrieveByPk($this->getMemberId());
  }

  public function getRegisterEndAction()
  {
    return $this->getAuthContainer()->getRegisterEndAction();
  }

  public function login($form)
  {
    $member_id = $this->getAuthContainer()->fetchData($form);

    if ($member_id) {
      $this->setAuthenticated(true);
      $this->setIsSNSMember(true);
      $this->setAttribute('member_id', $member_id, 'sfOpenPNESecurityUser');
    }

    return $this->isAuthenticated();
  }

  public function logout()
  {
    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('sfOpenPNESecurityUser');
    $this->getAttributeHolder()->removeNamespace('sfOpenPNESecurityUserProfile');
    $this->clearCredentials();
  }

  public function register($memberId = null, $form = null)
  {
    $isRegisterData = $this->getAuthContainer()->registerData($memberId, $form);
    if ($isRegisterData) {
      $this->setAuthenticated(true);
      $this->setAttribute('member_id', $memberId, 'sfOpenPNESecurityUser');
      return true;
    }

    return false;
  }

  public function initializeCredential()
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

    $this->setIsSNSRegisterBegin($this->getAuthContainer()->isRegisterBegin());
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

  public function getProfile($profileName)
  {
    if (!$this->isAuthenticated()) {
      return false;
    }

    return MemberProfilePeer::retrieveByMemberIdAndProfileName($this->getMemberId(), $profileName);
  }
}
