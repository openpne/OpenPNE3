<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision21_AddIsLoginRejectedColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('member', 'is_login_rejected', 'boolean', null, array(
      'notnull' => 1,
      'default' => 0,
    ));
  }

  public function down()
  {
    $this->removeColumn('member', 'is_login_rejected');
  }
}
