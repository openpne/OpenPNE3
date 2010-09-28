<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision47_ChangeCommunityCategory extends Doctrine_Migration_Base
{
  public function up()
  {
    $options = array('default' => 'NULL', 'notnull' => '1', 'comment' => 'Category name');
    $this->changeColumn('community_category', 'name', 'string', 64, $options);
  }
}
