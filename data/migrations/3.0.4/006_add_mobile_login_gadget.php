<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class addMobieLoginGadget extends opMigration
{
  public function up()
  {
    $criteria = new Criteria();
    $criteria->add(GadgetPeer::TYPE, 'mobileLoginContents');
    $criteria->add(GadgetPeer::NAME, 'loginForm');
    if (!GadgetPeer::doSelectOne($criteria))
    {
      $gadget = new Gadget();
      $gadget->setType('mobileLoginContents');
      $gadget->setName('loginForm');
      $gadget->setSortOrder(10);
      $gadget->save();
    }

  }

  public function down()
  {
  }
}
