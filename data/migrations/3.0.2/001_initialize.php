<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class initialize extends opMigration
{
  public function up()
  {
    try
    {
      $conn = Doctrine_Manager::connection();

      $conn->export->alterTable('member', array('add' => array(
        'invite_member_id' => array(
          'type' => 'integer',
          'size' => 11,
        ),
      )));

      $conn->export->createForeignKey('member', array(
        'name'         => 'member_FI_1',
        'local'        => 'invite_member_id',
        'foreign'      => 'id',
        'foreignTable' => 'member',
        'onDelete'     => 'SET NULL'
      ));
    }
    catch (Doctrine_Connection_Exception $e)
    {
      // FIXME: this script should check only "column already exists" error.
      $syntaxErrorCode = 42;

      // do nothing when the error is "already exists" because it is caused the following reasons:
      //   * the SNS has been created or rebuilt on since 3.0.1
      //   * the "openpne:update" task has been executed
      if ($e->getCode() != $syntaxErrorCode)
      {
        throw $e;
      }
    }
  }
}
