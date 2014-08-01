<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy for cleaning-up imported member images.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Hidenori Goto <hidenorigoto@gmail.com>
 */
class opUpgradeFrom2MemberImageCleanupStrategy extends opUpgradeAbstractStrategy
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
    $this->conn->execute('DELETE FROM member_image WHERE file_id = 0');
  }
}
