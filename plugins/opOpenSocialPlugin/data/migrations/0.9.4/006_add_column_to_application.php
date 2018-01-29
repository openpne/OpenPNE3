<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPlugin6_AddColumnToApplication extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->column($direction, 'application', 'member_id', 'integer', 4, array(
      'notnull' => false,
    ));

    $this->column($direction, 'application', 'is_active', 'boolean', null, array(
      'notnull' => true,
      'default' => true
    ));
  }
}
