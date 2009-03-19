<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * addProfilePublicFlagColumn
 * This is a migrate script for revision '3'
 *
 * @package OpenPNE 
 * @author  Shogo Kawahara <kawahara@tejimaya.net>
 */
class addProfilePublicFlagColumn extends opMigration
{
  public function up()
  {
    $this->addColumn('profile', 'is_edit_public_flag', 'boolean', array(
      'default' => 0,
      'notnull' => true
    ));
    $this->addColumn('profile', 'default_public_flag', 'integer', array(
      'length' => 4,
      'default' => 1,
      'notnull' => true
    ));
    $this->addColumn('member_profile', 'public_flag', 'integer', array(
      'length' => 4,
      'notnull' => false
    ));
  }

  public function postUp()
  {
    $memberProfiles = MemberProfilePeer::doSelect(new Criteria());
    foreach ($memberProfiles as $memberProfile)
    {
      if ($memberProfile->isRoot())
      {
        $memberProfile->setPublicFlag(1);
        $memberProfile->save();
      }
    }
  }

  public function down()
  {
    $this->removeColumn('profile', 'is_edit_public_flag');
    $this->removeColumn('profile', 'default_public_flag');
    $this->removeColumn('member_profile', 'public_flag');
  }
}
