<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class renameColumnsForDoctrine extends Doctrine_Migration_Base
{

  public function up()
  {

    $columns = array(
      'is_receive_mail_pc' => array(
        'type' => 'boolean',
        'notnull' => 1
        'default' => false
      ),
      'is_receive_mail_mobile' => array(
        'type' => 'boolean',
        'notnull' => 1
        'default' => false
      ),
    );

    // community_member
    $this->addColumn('community_member', $columns);
  }

  public function down()
  {
    // community_member
    $this->removeColumn('community_member', 'is_receive_mail_pc');
    $this->removeColumn('community_member', 'is_receive_mail_mobile');
  }
}
