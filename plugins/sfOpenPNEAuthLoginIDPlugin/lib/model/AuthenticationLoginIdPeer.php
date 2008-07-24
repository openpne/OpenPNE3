<?php

/**
 * Subclass for performing query and update operations on the 'authentication_login_id' table.
 *
 * 
 *
 * @package plugins.sfOpenPNEAuthContainer_LoginID.lib.model
 */ 
class AuthenticationLoginIdPeer extends BaseAuthenticationLoginIdPeer
{
  static public function retrieveByLoginId($loginId)
  {
    $c = new Criteria();
    $c->add(self::LOGIN_ID, $loginId);
    return self::doSelectOne($c);
  }
}
