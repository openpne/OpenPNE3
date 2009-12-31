<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision38_AddMemberConfigHashColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $options = array('notnull' => true, 'comment' => 'Hash value for searching name & value');
    $this->addColumn('member_config', 'name_value_hash', 'string', '32', $options);
  }

  public function postUp()
  {
    Doctrine_Query::create()
      ->update('MemberConfig')
      ->set('name_value_hash', new Doctrine_Expression('MD5(CONCAT(`name`, ",", `value`))'))
      ->execute();
  }

  public function down()
  {
    $this->removeColumn('member_config', 'name_value_hash');
  }
}
