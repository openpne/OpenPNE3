<?php

class BannerUseImagePeer extends BaseBannerUseImagePeer
{
  static public function retrieveByBannerImageId($bannerImageId)
  {
    $c = new Criteria();
    $c->add(self::BANNER_IMAGE_ID, $bannerImageId);

    return self::doSelectOne($c);
  }
}
