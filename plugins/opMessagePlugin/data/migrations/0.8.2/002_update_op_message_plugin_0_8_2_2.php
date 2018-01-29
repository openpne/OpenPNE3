<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class updateOpMessagePlugin_0_8_2_2 extends opMigration
{
  public function up()
  {
    $criteria = new Criteria();
    $criteria->add(NavigationPeer::TYPE, 'mobile_home_side');
    $criteria->add(NavigationPeer::URI, 'message/index');
    if (!NavigationPeer::doSelectOne($criteria))
    {
      $navigation = new Navigation();
      $navigation->setType('mobile_home_side');
      $navigation->setUri('message/index');
      $navigation->setSortOrder(20);
      $navigation->setCulture('ja_JP');
      $navigation->setCaption('ﾒｯｾｰｼﾞ');
      $navigation->setCulture('en');
      $navigation->setCaption('Message');
      $navigation->save();
    }
    
  }

  public function down()
  {
  }
}
