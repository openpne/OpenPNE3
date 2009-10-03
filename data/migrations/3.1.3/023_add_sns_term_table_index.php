<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision23_AddSnsTermTableIndex extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->foreignKey($direction, 'sns_term_translation', 'sns_term_translation_id_sns_term_id', array(
      'name' => 'sns_term_translation_id_sns_term_id',
      'local' => 'id',
      'foreign' => 'id',
      'foreignTable' => 'sns_term',
      'onUpdate' => 'CASCADE',
      'onDelete' => 'CASCADE',
    ));
    $this->index($direction, 'sns_term_translation', 'sns_term_translation_id', array(
      'fields' => array(0 => 'id'),
    ));
  }
}
