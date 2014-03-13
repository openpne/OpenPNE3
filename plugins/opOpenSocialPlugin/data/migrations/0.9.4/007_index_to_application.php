<?php
/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPlugin7_IndexToApplication extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->foreignKey($direction, 'application', 'application_member_id_member_id', array(
      'name' => 'application_member_id_member_id',
      'local' => 'member_id',
      'foreign' => 'id',
      'foreignTable' => 'member',
      'onDelete' => 'SET NULL',
    ));
  }
}
