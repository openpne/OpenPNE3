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
class sfOpenPNESecurityUser extends opBaseSecurityUser
{
  protected
    $authAdapters = array();

  /**
   * Initializes the current user.
   *
   * @see sfBasicSecurityUser
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);
    if ($this->getMemberId() && $this->isTimedOut())
    {
      $this->getAttributeHolder()->removeNamespace('sfOpenPNESecurityUser');
    }

    $request = sfContext::getInstance()->getRequest();
    $authMode = $request->getUrlParameter('authMode');
    if ($authMode)
    {
      $this->setCurrentAuthMode($authMode);
    }

    $this->createAuthAdapter($this->getCurrentAuthMode());

    $this->initializeCredentials();
  }

  public function getAuthAdapters()
  {
    $adapters = array();
    $plugins = sfContext::getInstance()->getConfiguration()->getEnabledAuthPlugin();

    foreach ($plugins as $pluginName)
    {
      $endPoint = strlen($pluginName) - strlen('opAuth') - strlen('Plugin');
      $authMode = substr($pluginName, strlen('opAuth'), $endPoint);
      $adapterClass = self::getAuthAdapterClassName($authMode);
      $adapters[$authMode] = new $adapterClass($authMode);
    }

    return $adapters;
  }

  public function getAuthModes()
  {
    $is_mobile = sfConfig::get('app_is_mobile', false);
    $result = array();

    $adapters = $this->getAuthAdapters();
    foreach ($adapters as $authMode => $adapter)
    {
      if (($is_mobile && !$adapter->getAuthConfig('enable_mobile'))
        || (!$is_mobile && !$adapter->getAuthConfig('enable_pc')))
      {
        continue;
      }

      $result[] = $authMode;
    }

    return $result;
  }

  public function getAuthAdapter($authMode = null)
  {
    if (!$authMode)
    {
      $authMode = $this->getCurrentAuthMode();
    }

    $this->createAuthAdapter($authMode);

    return $this->authAdapters[$authMode];
  }

  public function createAuthAdapter($authMode)
  {
    if (empty($this->authAdapters[$authMode]))
    {
      $containerClass = self::getAuthAdapterClassName($authMode);
      $this->authAdapters[$authMode] = new $containerClass($authMode);
    }
  }

  public function getAuthForm()
  {
    return $this->getAuthAdapter()->getAuthForm();
  }

  public function getAuthForms()
  {
    $result = array();

    $authModes = $this->getAuthModes();
    foreach ($authModes as $authMode)
    {
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
    $this->createAuthAdapter($this->getCurrentAuthMode());
  }

  public function getCurrentAuthMode($allowGuess = true)
  {
    $authMode = $this->getAttribute('auth_mode', null, 'sfOpenPNESecurityUser');

    $authModes = $this->getAuthModes();
    if (!in_array($authMode, $authModes))
    {
      if ($allowGuess)
      {
        $authMode = array_shift($authModes);
      }
      else
      {
        $authMode = null;
      }
    }

    return $authMode;
  }

  public function getMember()
  {
    return Doctrine::getTable('Member')->find($this->getMemberId());
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

      opActivateBehavior::disable();
      if ($this->getMember()->isOnBlacklist())
      {
        opActivateBehavior::enable();
        $this->logout();

        return false;
      }
      opActivateBehavior::enable();

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
    $authMode = $this->getCurrentAuthMode();

    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('sfOpenPNESecurityUser');
    $this->clearCredentials();

    $this->setCurrentAuthMode($authMode);
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

    opActivateBehavior::disable();
    if (!$this->getMember())
    {
      opActivateBehavior::enable();
      return false;
    }
    if ($this->getMember()->getIsLoginRejected())
    {
      opActivateBehavior::enable();
      $this->logout();

      return false;
    }
    opActivateBehavior::enable();

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
}
