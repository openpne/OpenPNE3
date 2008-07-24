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

  public function login($form)
  {
    $member_id = $this->getAuthContainer()->fetchData($form);

    if ($member_id) {
      $this->setAuthenticated(true);
      $this->setIsSNSMember(true);
      $this->setAttribute('member_id', $member_id, 'sfOpenPNESecurityUser');
    } else {
      $this->logout();
    }

    return $this->isAuthenticated();
  }

  public function logout()
  {
    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('sfOpenPNESecurityUser');
    $this->clearCredential();
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
}
