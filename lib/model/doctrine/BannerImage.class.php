<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class BannerImage extends BaseBannerImage
{
  public function delete(Doctrine_Connection $conn = null)
  {
    $bannerUseImageList = Doctrine::getTable('BannerUseImage')->findByBannerImageId($this->getId());
    foreach ($bannerUseImageList as $bannerUseImage)
    {
      $bannerUseImage->delete();
    }

    return parent::delete($conn);
  }
}
