<?php

class BannerUseImagePeer extends BaseBannerUseImagePeer
{
  static public function retrieveByBannerId($bannerId)
  {
    $c = new Criteria();
    $c->add(self::BANNER_ID, $bannerId);

    return self::doSelect($c);
  }

  static public function retrieveByBannerAndImageId($bannerId, $bannerImageId)
  {
    $c = new Criteria();
    $c->add(self::BANNER_ID, $bannerId);
    $c->add(self::BANNER_IMAGE_ID, $bannerImageId);

    return self::doSelectOne($c);
  }
}
