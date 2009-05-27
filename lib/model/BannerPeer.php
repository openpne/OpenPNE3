<?php

class BannerPeer extends BaseBannerPeer
{
  static public function retrieveTop()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(self::ID);

    return self::doSelectOne($c);
  }

  static public function retrievesAll()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(self::ID);

    return self::doSelect($c);
  }

  static public function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    return self::doSelectOne($c);
  }
}
