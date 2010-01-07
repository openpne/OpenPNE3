<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision40_ChangeFileForeignKey extends Doctrine_Migration_Base
{
  public function preUp()
  {
    Doctrine::getTable('FileBin')->createQuery()->delete()
      ->where('FileBin.file_id NOT IN (SELECT File.id FROM File)')
      ->execute();
  }

  public function up()
  {
    $this->createForeignKey('file_bin', 'file_bin_file_id_file_id', array(
      'name' => 'file_bin_file_id_file_id',
      'local' => 'file_id',
      'foreign' => 'id',
      'foreignTable' => 'file',
      'onUpdate' => '',
      'onDelete' => 'cascade',
    ));
  }
}
