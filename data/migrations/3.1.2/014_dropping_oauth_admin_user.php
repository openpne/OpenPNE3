<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class droppingOAuthAdminUser extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->removeColumn('o_auth_admin_token', 'admin_user_id');
  }

  public function down()
  {
    $this->addColumn('o_auth_admin_token', 'admin_user_id', 'integer', 4, array(
      'notnull' => '1',
    ));
  }
}

