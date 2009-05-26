<?php

class BannerImage extends BaseBannerImage
{
  public function delete(PropelPDO $con = null)
  {
    $c = new Criteria();
    $c->add(BannerUseImagePeer::BANNER_IMAGE_ID, $this->getId());
    $bannerUseImageList = BannerUseImagePeer::doSelect($c);
    foreach($bannerUseImageList as $bannerUseImage)
    {
      $bannerUseImage->delete();
    }

    return parent::delete($con);
  }
}
