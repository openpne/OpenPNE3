<?php

/**
 * Subclass for performing query and update operations on the 'sns_config' table.
 *
 * 
 *
 * @package lib.model
 */ 
class SnsConfigPeer extends BaseSnsConfigPeer
{
  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    $result = self::doSelectOne($c);
    return $result;
  }
}
