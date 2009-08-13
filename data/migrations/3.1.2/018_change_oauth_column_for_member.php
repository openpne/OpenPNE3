<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision18_changeOAuthColumnForMember extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->changeColumn('o_auth_member_token', 'member_id', '4', 'integer', array(
      'notnull' => '0',
      'comment' => 'Member id',
    ));
  }

  public function down()
  {
    $this->changeColumn('o_auth_member_token', 'member_id', '4', 'integer', array(
      'notnull' => '1',
      'comment' => 'Member id',
    ));
  }
}
