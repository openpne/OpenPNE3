<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision48_FixBannerUseImageConstraint extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->dropForeignKey('banner_use_image', 'banner_use_image_banner_id_banner_id');
    $this->dropForeignKey('banner_use_image', 'banner_use_image_banner_image_id_banner_image_id');

    $this->createForeignKey('banner_use_image', 'banner_use_image_banner_id_banner_id', array(
      'local' => 'banner_id',
      'foreignTable' => 'banner',
      'foreign' => 'id',
      'onDelete' => 'cascade',
    ));

    $this->createForeignKey('banner_use_image', 'banner_use_image_banner_image_id_banner_image_id', array(
      'local' => 'banner_image_id',
      'foreignTable' => 'banner_image',
      'foreign' => 'id',
      'onDelete' => 'cascade',
    ));
  }
}
