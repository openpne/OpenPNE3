<?php

class BannerImagePeer extends BaseBannerImagePeer
{
  static public function retrievesAll()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(self::ID);

    return self::doSelect($c);
  }
}
