<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class myUser extends opBaseSecurityUser
{
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);

    $adminUserId = Doctrine::getTable('AdminUser')->find($this->getId());
    if (!$adminUserId)
    {
      $this->logout();
    }
  }

  public function getId()
  {
    return $this->getAttribute('adminUserId', null, 'adminUser');
  }

  public function login($adminUserId)
  {
    $this->setAuthenticated(true);
    $this->setAttribute('adminUserId', $adminUserId, 'adminUser');
  }

  public function logout()
  {
    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('adminUser');
    $this->clearCredentials();
  }
}
