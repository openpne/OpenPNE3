<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opUpdate_3_0beta4_dev_200901220252_to_3_0beta4_dev_200901220253 extends opUpdate
{
  public function update()
  {
    $this->createForeignKey('gadget_config', array(
      'local'        => 'gadget_id',
      'foreign'      => 'id',
      'foreignTable' => 'gadget',
      'onDelete'     => 'CASCADE',
    ));
  }
}
