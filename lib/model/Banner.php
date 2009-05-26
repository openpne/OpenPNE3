<?php

class Banner extends BaseBanner
{
  public function getRandomImage()
  {
    $c = new Criteria();
    $c->add(BannerUseImagePeer::BANNER_ID, $this->getId());
    $c->addAscendingOrderByColumn('rand()');
    $bannerUseImage = BannerUseImagePeer::doSelectOne($c);
    if (!$bannerUseImage)
    {
      return false;
    }

    return $bannerUseImage->getBannerImage();
  }
}
