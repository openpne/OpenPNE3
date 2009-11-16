<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class changingOAuthTables extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->removeColumn('oauth_consumer', 'type');
    $this->removeColumn('o_auth_admin_token', 'expires_at');
    $this->addColumn('o_auth_admin_token', 'callback_url', 'string');
    $this->addColumn('o_auth_admin_token', 'verifier', 'string');
  }

  public function down()
  {
    $this->addColumn('oauth_consumer', 'type', 'enum', null, array(
      'values'  => array('browser', 'client'),
      'default' => 'browser',
    ));
    $this->addColumn('o_auth_admin_token', 'expires_at', 'timestamp');
    $this->removeColumn('o_auth_admin_token', 'callback_url', 'string');
  }
}
