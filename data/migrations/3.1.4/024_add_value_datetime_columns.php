<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision24_AddValueDatetimeColumns extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->column($direction, 'member_config', 'value_datetime', 'datetime');

    $this->column($direction, 'member_profile', 'value_datetime', 'datetime');

    if ('up' === $direction)
    {
      $this->changeColumn('member_profile', 'value', '', 'string', array(
        'notnull' => '1',
        'comment' => 'Text content for this profile item',
      ));
      $this->changeColumn('member_config', 'value', '', 'string', array(
        'notnull' => '1',
        'comment' => 'Configuration value',
      ));
    }
    else
    {
      $this->changeColumn('member_profile', 'value', null, 'string', array(
        'notnull' => '0',
        'comment' => 'Text content for this profile item',
      ));
      $this->changeColumn('member_config', 'value', null, 'string', array(
        'notnull' => '0',
        'comment' => 'Configuration value',
      ));
    }
  }

  public function postUp()
  {
    $profile = Doctrine::getTable('Profile')->findOneByName('op_preset_birthday');
    if (!$profile)
    {
      return false;
    }

    $conn = Doctrine_Manager::connection();
    $conn->update(Doctrine::getTable('MemberProfile'), array('value_datetime' => new Doctrine_Expression('value')), array('profile_id' => $profile->id));
  }
}
