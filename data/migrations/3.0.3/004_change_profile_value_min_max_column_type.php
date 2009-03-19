<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class changeProfileValueMinMaxColumnType extends opMigration
{
  public function up()
  {
    $option = array(
      'length'  => 32,
    );

    $this->changeColumn('profile', 'value_min', 'string', $option);
    $this->changeColumn('profile', 'value_max', 'string', $option);
  }

  public function postUp()
  {
    $birthday = ProfilePeer::retrieveByName('birthday');
    if ($birthday)
    {
      $birthday->setValueMin('-100years');
      $birthday->setValueMax('now');
      $birthday->save();
    }
  }

  public function down()
  {
    $option = array(
      'length'  => 4,
    );

    $this->changeColumn('profile', 'value_min', 'integer', $option);
    $this->changeColumn('profile', 'value_max', 'integer', $option);

    $birthday = ProfilePeer::retrieveByName('birthday');
    if ($birthday)
    {
      $birthday->setValueMin(null);
      $birthday->setValueMax(null);
      $birthday->save();
    }
  }
}
