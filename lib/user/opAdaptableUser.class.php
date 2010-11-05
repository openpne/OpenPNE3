<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAdaptableUser will handle auth adapters
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class opAdaptableUser extends opBaseSecurityUser
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
}
