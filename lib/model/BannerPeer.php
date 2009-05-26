<?php

class BannerPeer extends BaseBannerPeer
{
  static public function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    return self::doSelectOne($c);
  }
}
