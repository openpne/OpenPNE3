<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */
class Banner extends BaseBanner
{
  public function getRandomImage()
  {
    $bannerUseImage = Doctrine::getTable('BannerUseImage')->createQuery($this->getId())
      ->where('banner_id = ?', $this->getId())
      ->orderBy(Doctrine_Manager::connection()->expression->random())
      ->limit(1)
      ->fetchOne();

    if (!$bannerUseImage)
    {
      return false;
    }

    return $bannerUseImage->getBannerImage();
  }
}
