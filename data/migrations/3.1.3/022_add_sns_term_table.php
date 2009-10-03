<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision22_AddSnsTermTable extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'sns_term', array(
      'id' => array(
        'type' => 'integer',
        'primary' => '1',
        'autoincrement' => '1',
        'comment' => 'Serial number',
        'length' => '4',
      ),
      'name' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Term name',
        'length' => '64',
      ),
      'application' => array(
         'type' => 'string',
         'default' => 'pc_frontend',
         'notnull' => '1',
         'comment' => 'Application name',
         'length' => '32',
      )), array(
        'type' => 'INNODB',
        'indexes' => array(
          'application_INDEX' => array('fields' => array(0 => 'application'))
        ),
        'primary' => array(0 => 'id'),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
    ));
    $this->table($direction, 'sns_term_translation', array(
      'id' => array(
        'type' => 'integer',
        'comment' => 'Serial number',
        'length' => '4',
        'primary' => '1',
      ),
      'value' => array(
        'type' => 'string',
        'comment' => 'Term value',
        'length' => '',
      ),
      'lang' => array(
        'fixed' => '1',
        'primary' => '1',
        'type' => 'string',
        'length' => '5',
      )), array(
        'type' => 'INNODB',
        'primary' => array(0 => 'id', 1 => 'lang'),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
    ));
  }
}
