<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSecurityUser will handle credential for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opSecurityUser extends sfBasicSecurityUser
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
      $this->getAttributeHolder()->removeNamespace('opSecurityUser');
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
    return $this->getAttribute('member_id', null, 'opSecurityUser');
  }

  public function setMemberId($memberId)
  {
    return $this->setAttribute('member_id', $memberId, 'opSecurityUser');
  }

  public function setCurrentAuthMode($authMode)
  {
    $this->setAttribute('auth_mode', $authMode, 'opSecurityUser');
    $this->createAuthAdapter($this->getCurrentAuthMode());
  }

  public function getCurrentAuthMode($allowGuess = true)
  {
    $authMode = $this->getAttribute('auth_mode', null, 'opSecurityUser');

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
    if (!$this->getMemberId())
    {
      return new opAnonymousMember();
    }

    return Doctrine::getTable('Member')->find($this->getMemberId());
  }

  public function getRegisterEndAction()
  {
    return $this->getAuthAdapter()->getRegisterEndAction();
  }


 /**
  * get remember login cookie
  *
  * @return array
  */
  protected function getRememberLoginCookie()
  {
    $key = md5(sfContext::getInstance()->getRequest()->getHost());
    if ($value = sfContext::getInstance()->getRequest()->getCookie($key))
    {
      $value = unserialize(base64_decode($value));
      return $value;
    }
    return null;
  }

 /**
  * set remember login cookie
  */
  protected function setRememberLoginCookie($isDeleteCookie = false)
  {
    $key = md5(sfContext::getInstance()->getRequest()->getHost());
    $path = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();
    if (!$path)
    {
      $path = '/';
    }

    if ($isDeleteCookie)
    {
      if (!sfContext::getInstance()->getRequest()->getCookie($key))
      {
        return;
      }

      if ($this->getMemberId())
      {
        $this->getMember()->setConfig('remember_key', '');
      }

      $value = null;
      $expire = time() - 3600;
    }
    else
    {
      $rememberKey = opToolkit::generatePasswordString();
      if (!$this->getMemberId())
      {
        throw new LogicException('No login');
      }
      $this->getMember()->setConfig('remember_key', $rememberKey);

      $value = base64_encode(serialize(array($this->getMemberId(), $rememberKey)));
      $expire = time() + sfConfig::get('op_remember_login_limit', 60*60*24*30);
    }
    sfContext::getInstance()->getResponse()->setCookie($key, $value, $expire, $path, '', false, true);
  }

 /**
  * get memberd member id
  *
  * @return integer the member id  
  */
  public function getRememberedMemberId()
  {
    if (($value = $this->getRememberLoginCookie()) && 2 == count($value))
    {
      if ($value[0] && $value[1])
      {
        $memberConfig = Doctrine::getTable('MemberConfig')->findOneByMemberIdAndNameAndValue($value[0], 'remember_key', $value[1]);
        if ($memberConfig)
        {
          $expire = strtotime($memberConfig->getUpdatedAt()) + sfConfig::get('op_remember_login_limit', 60*60*24*30);
          if ($expire > time())
          {
            return $value[0];
          }
        }
      }
    }
    return null;
  }

 /**
  * Login
  *
  * @param integer $memberId the member id
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function login($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = $this->getAuthAdapter()->authenticate();
    }

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

    if ($this->getAuthAdapter()->getAuthForm()->getValue('is_remember_me'))
    {
      $this->setRememberLoginCookie();
    }

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

    $this->setRememberLoginCookie(true);

    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('opSecurityUser');
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
      $this->setAttribute('member_id', $result, 'opSecurityUser');
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

  public function isMember()
  {
    return $this->isSNSMember();
  }

  public function isSNSMember()
  {
    return $this->hasCredential('SNSMember');
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
