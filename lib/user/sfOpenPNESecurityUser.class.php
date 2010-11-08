<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfOpenPNESecurityUser will handle credential for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNESecurityUser extends sfBasicSecurityUser
{
  const SITE_IDENTIFIER_NAMESPACE = 'OpenPNE/user/sfOpenPNESecurityUser/site_identifier';

  protected $authAdapter = null;

  /**
   * Initializes the current user.
   *
   * @see sfBasicSecurityUser
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    if (!isset($options['session_namespaces']))
    {
      $options['session_namespaces'] = array(
        self::SITE_IDENTIFIER_NAMESPACE,
        self::LAST_REQUEST_NAMESPACE,
        self::AUTH_NAMESPACE,
        self::CREDENTIAL_NAMESPACE,
        self::ATTRIBUTE_NAMESPACE,
      );
    }

    parent::initialize($dispatcher, $storage, $options);

    $request = sfContext::getInstance()->getRequest();
    $authMode = $request->getUrlParameter('authMode');
    if ($authMode)
    {
      $this->setCurrentAuthMode($authMode);
    }

    $containerClass = self::getAuthAdapterClassName($this->getCurrentAuthMode());
    $this->authAdapter = new $containerClass($this->getCurrentAuthMode());

    if (!$this->isValidSiteIdentifier())
    {
      // This session is not for this site.
      $this->logout();

      // So we need to clear all data of the current session because they might be tainted by attacker.
      // If OpenPNE uses that tainted data, it may cause limited session fixation attack.
      $this->clearSessionData();

      return null;
    }

    $this->initializeCredentials();
  }

  public function clearSessionData()
  {
    // remove data in storage
    foreach ($this->options['session_namespaces'] as $v)
    {
      $this->storage->remove($v);
    }

    // remove attribtues
    $this->attributeHolder->clear();
  }

  public function isValidSiteIdentifier()
  {
    if (!sfConfig::get('op_check_session_site_identifier', true))
    {
      return true;
    }

    return ($this->generateSiteIdentifier() === $this->storage->read(self::SITE_IDENTIFIER_NAMESPACE));
  }

  public function generateSiteIdentifier()
  {
    $request = sfContext::getInstance()->getRequest();
    $identifier = $request->getUriPrefix().$request->getRelativeUrlRoot();

    return $identifier;
  }

  public function getAuthModes()
  {
    $is_mobile = sfConfig::get('app_is_mobile', false);
    $plugins = sfContext::getInstance()->getConfiguration()->getEnabledAuthPlugin();

    $result = array();

    foreach ($plugins as $pluginName)
    {
      $endPoint = strlen($pluginName) - strlen('opAuth') - strlen('Plugin');
      $authMode = substr($pluginName, strlen('opAuth'), $endPoint);

      $adapterClass = self::getAuthAdapterClassName($authMode);
      $adapter = new $adapterClass($authMode);
      if (($is_mobile && !$adapter->getAuthConfig('enable_mobile'))
        || (!$is_mobile && !$adapter->getAuthConfig('enable_pc')))
      {
        continue;
      }

      $result[] = $authMode;
    }

    return $result;
  }

  public function getAuthAdapter()
  {
    return $this->authAdapter;
  }

  public function getAuthForm()
  {
    return $this->getAuthAdapter()->getAuthForm();
  }

  public function getAuthForms()
  {
    $result = array();

    $authModes = $this->getAuthModes();
    foreach ($authModes as $authMode) {
      $adapterClass = self::getAuthAdapterClassName($authMode);
      $adapter = new $adapterClass($authMode);
      $result[$authMode] = $adapter->getAuthForm();
    }

    return $result;
  }

  public static function getAuthAdapterClassName($authMode)
  {
    return 'opAuthAdapter'.ucfirst($authMode);
  }

  public function getMemberId()
  {
    return $this->getAttribute('member_id', null, 'sfOpenPNESecurityUser');
  }

  public function setMemberId($memberId)
  {
    return $this->setAttribute('member_id', $memberId, 'sfOpenPNESecurityUser');
  }

  public function setCurrentAuthMode($authMode)
  {
    $this->setAttribute('auth_mode', $authMode, 'sfOpenPNESecurityUser');
  }

  public function getCurrentAuthMode()
  {
    $authMode = $this->getAttribute('auth_mode', null, 'sfOpenPNESecurityUser');

    $authModes = $this->getAuthModes();
    if (!in_array($authMode, $authModes))
    {
      $authMode = array_shift($authModes);
      $this->setCurrentAuthMode($authMode);
    }

    return $authMode;
  }

  public function getMember()
  {
    return MemberPeer::retrieveByPk($this->getMemberId());
  }

  public function getRegisterEndAction()
  {
    return $this->getAuthAdapter()->getRegisterEndAction();
  }

 /**
  * Login
  *
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function login()
  {
    $memberId = $this->getAuthAdapter()->authenticate();

    if ($memberId)
    {
      $this->setMemberId($memberId);

      if ($this->getMember()->isOnBlacklist())
      {
        $this->logout();
        return false;
      }

      $this->setAuthenticated(true);
    }

    $this->initializeCredentials();

    if ($this->isAuthenticated())
    {
      $this->setCurrentAuthMode($this->getAuthAdapter()->getAuthModeName());
      $uri = $this->getAuthAdapter()->getAuthForm()->getValue('next_uri');
      return $uri;
    }

    return false;
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
    $result = $this->getAuthAdapter()->register($form);
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
    $isRegisterFinish = $this->getAuthAdapter()->isRegisterFinish($memberId);
    $isRegisterBegin = $this->getAuthAdapter()->isRegisterBegin($memberId);

    $this->setIsSNSMember(false);
    $this->setIsSNSRegisterBegin(false);
    $this->setIsSNSRegisterFinish(false);

    if (!$this->getMember())
    {
      return false;
    }

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


 /**
  * @deprecated This method overrides sfBasicSecurityUser::removeCredential() bacause
  * we want to remove a function call sfSessionStorage::regenerate() for CSRF protection
  * becomes working correctly.
  * But this solution is a very stupid. We must decrease this function call.
  */
  public function removeCredential($credential)
  {
    if ($this->hasCredential($credential))
    {
      foreach ($this->credentials as $key => $value)
      {
        if ($credential == $value)
        {
          if ($this->options['logging'])
          {
            $this->dispatcher->notify(new sfEvent($this, 'application.log', array(sprintf('Remove credential "%s"', $credential))));
          }

          unset($this->credentials[$key]);

          return;
        }
      }
    }
  }

 /**
  * @deprecated This method overrides sfBasicSecurityUser::addCredentials() bacause
  * we want to remove a function call sfSessionStorage::regenerate() for CSRF protection
  * becomes working correctly.
  * But this solution is a very stupid. We must decrease this function call.
  */
  public function addCredentials()
  {
    if (func_num_args() == 0) return;

    // Add all credentials
    $credentials = (is_array(func_get_arg(0))) ? func_get_arg(0) : func_get_args();

    if ($this->options['logging'])
    {
      $this->dispatcher->notify(new sfEvent($this, 'application.log', array(sprintf('Add credential(s) "%s"', implode(', ', $credentials)))));
    }

    $added = false;
    foreach ($credentials as $aCredential)
    {
      if (!in_array($aCredential, $this->credentials))
      {
        $added = true;
        $this->credentials[] = $aCredential;
      }
    }
  }

  public function shutdown()
  {
    $this->storage->write(self::SITE_IDENTIFIER_NAMESPACE, $this->generateSiteIdentifier());

    parent::shutdown();
  }
}
