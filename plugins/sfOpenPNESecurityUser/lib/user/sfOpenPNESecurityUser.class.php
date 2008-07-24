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

    $driver = 'PCAddress';

    $containerClass = 'sfOpenPNEAuthContainer_' . $driver;
    $this->authContainer = new $containerClass();

    $formClass = 'sfOpenPNEAuthForm_' . $driver;
    $this->authForm = new $formClass();

    $this->logout();
  }

  public function getAuthContainer()
  {
    return $this->authContainer;
  }

  public function getAuthForm()
  {
    return $this->authForm;
  }

  public function login($form)
  {
    $this->user = $this->getAuthContainer()->fetchData($form);

    if ($this->user) {
        $this->setAuthenticated(true);
    } else {
        $this->logout();
    }
  }

  public function logout()
  {
    $this->setAuthenticated(false);
    $this->user = null;
  }
}
