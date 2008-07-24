<?php

/**
 * Subclass for performing query and update operations on the 'authentication_pc_address' table.
 *
 * 
 *
 * @package plugins.sfOpenPNEAuthContainer_PCAddress.lib.model
 */ 
class AuthenticationPcAddressPeer extends BaseAuthenticationPcAddressPeer
{
  static public function retrieveByPcAddress($pcAddress)
  {
    $c = new Criteria();
    $c->add(self::PC_ADDRESS, $pcAddress);
    return self::doSelectOne($c);
  }
}
