<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision45_InsertBirthdayGadget extends Doctrine_Migration_Base
{
  public function up()
  {
    $gadget = new Gadget();
    $gadget->setType('top');
    $gadget->setName('birthdayBox');
    $gadget->setSortOrder(0);
    $gadget->save();

    $gadget = new Gadget();
    $gadget->setType('mobileTop');
    $gadget->setName('birthdayBox');
    $gadget->setSortOrder(0);
    $gadget->save();

    $gadget = new Gadget();
    $gadget->setType('profileTop');
    $gadget->setName('birthdayBox');
    $gadget->setSortOrder(0);
    $gadget->save();

    $gadget = new Gadget();
    $gadget->setType('mobileProfileTop');
    $gadget->setName('birthdayBox');
    $gadget->setSortOrder(0);
    $gadget->save();
  }
}
