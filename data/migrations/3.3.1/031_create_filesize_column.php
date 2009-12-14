<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision31_CreateFilesizeColumn extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->column($direction, 'file', 'filesize', 'integer', '4', array(
      'default' => '0',
      'notnull' => '1',
      'comment' => 'File size',
    ));
  }

  public function postUp()
  {
    $conn = Doctrine_Manager::connection();
    $conn->execute('UPDATE file SET filesize = (SELECT LENGTH(bin) FROM file_bin WHERE file_id = id)');
  }
}
