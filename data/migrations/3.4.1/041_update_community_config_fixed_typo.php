<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision41_UpdateCommunityConfigFixedTypo extends Doctrine_Migration_Base
{
  public function up()
  {
    $values = Doctrine_Query::create()
      ->from('CommunityConfig')
      ->where('name = ?', 'register_poricy')
      ->execute();

    foreach($values as $value)
    {
      $value->setName('register_policy')->save();
    }
  }
}
