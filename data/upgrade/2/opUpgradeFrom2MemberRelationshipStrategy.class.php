<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing member_relationship.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2MemberRelationshipStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $this->getDatabaseManager();
    $this->conn = Doctrine_Manager::connection();

    $this->conn->beginTransaction();
    try
    {
      $this->doRun();

      $this->conn->commit();
    }
    catch (Exception $e)
    {
      $this->conn->rollback();

      throw $e;
    }
  }

  public function doRun()
  {
    $friends = $this->conn->fetchAssoc('SELECT c_member_id_from, c_member_id_to FROM c_friend');
    $friendConfirms = $this->conn->fetchAssoc('SELECT c_member_id_from, c_member_id_to FROM c_friend_confirm');
    $accessBlocks = $this->conn->fetchAssoc('SELECT c_member_id, c_member_id_block FROM c_access_block');

    $relations = array();

    foreach ($friends as $friend)
    {
      $relations[$friend['c_member_id_from']][$friend['c_member_id_to']]['friend'] = true;
    }
    unset($friends);

    foreach ($friendConfirms as $friendConfirm)
    {
      $relations[$friendConfirm['c_member_id_from']][$friendConfirm['c_member_id_to']]['friend_pre'] = true;
    }
    unset($friendConfirmss);

    foreach ($accessBlocks as $accessBlock)
    {
      $relations[$accessBlock['c_member_id']][$accessBlock['c_member_id_block']]['access_block'] = true;
    }
    unset($accessBlocks);

    foreach ($relations as $idFrom => $relation)
    {
      foreach ($relation as $idTo => $types)
      {
        $this->conn->execute('INSERT INTO member_relationship (member_id_from, member_id_to, is_friend, is_friend_pre, is_access_block, created_at, updated_at) VALUES(?, ?, ?, ?, ?, NOW(), NOW())', array(
          $idFrom, $idTo, (int)!empty($types['friend']), (int)!empty($types['friend_pre']), (int)!empty($types['access_block']),
        ));
      }
    }

    $this->conn->execute('DROP TABLE c_access_block');
    $this->conn->execute('DROP TABLE c_friend');
    $this->conn->execute('DROP TABLE c_friend_confirm');
  }
}
