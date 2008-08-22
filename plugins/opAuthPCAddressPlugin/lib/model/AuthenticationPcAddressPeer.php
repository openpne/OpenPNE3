<?php

/**
 * Subclass for performing query and update operations on the 'authentication_pc_address' table.
 *
 * 
 *
 * @package plugins.sfOpenPNEAuthPCAddressPlugin.lib.model
 */ 
class AuthenticationPcAddressPeer extends BaseAuthenticationPcAddressPeer
{
  public static function retrieveByRegisterSession($registerSession)
  {
    $c = new Criteria();
    $c->add(self::REGISTER_SESSION, $registerSession);
    $result = self::doSelectOne($c);
    return $result;
  }

  public static function retrieveByMemberId($memberId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID, $memberId);
    $result = self::doSelectOne($c);
    return $result;
  }
}
