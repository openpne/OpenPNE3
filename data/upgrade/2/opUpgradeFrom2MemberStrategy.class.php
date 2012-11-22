<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing member.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     tatsuya ichikawa <ichikawa@tejimaya.com>
 */
class opUpgradeFrom2MemberStrategy extends opUpgradeAbstractStrategy
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
    $this->conn->execute('INSERT INTO member (id, name, is_active, is_login_rejected, created_at, updated_at) (SELECT c_member_id, nickname, 1, is_login_rejected, r_date, u_datetime FROM c_member)');

    $tableStatus = $this->conn->fetchArray('SHOW TABLE STATUS LIKE "c_member"');
    $autoIncrement = $tableStatus[10];
    $this->conn->execute("ALTER TABLE member AUTO_INCREMENT = $autoIncrement");
    $this->conn->execute('UPDATE member, c_member SET invite_member_id = c_member_id_invite WHERE id = c_member_id AND c_member_id_invite <> 0;');
  }
}
