<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision15_renameColumnsForDoctrine extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('community_member', 'is_receive_mail_pc', 'boolean', null, array(
      'notnull' => 1,
      'default' => 0,
    ));

    $this->addColumn('community_member', 'is_receive_mail_mobile', 'boolean', null, array(
      'notnull' => 1,
      'default' => 0,
    ));
  }

  public function down()
  {
    // community_member
    $this->removeColumn('community_member', 'is_receive_mail_pc');
    $this->removeColumn('community_member', 'is_receive_mail_mobile');
  }
}
