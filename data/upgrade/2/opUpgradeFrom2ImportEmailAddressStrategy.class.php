<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing member's email address.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2ImportEmailAddressStrategy extends opUpgradeAbstractStrategy
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
    set_include_path(dirname(__FILE__).'/lib'.PATH_SEPARATOR.get_include_path());
    require_once 'OpenPNE2Util.class.php';

    $results = $this->conn->fetchAssoc('SELECT c_member_id, pc_address, ktai_address FROM c_member_secure');
    foreach ($results as $result)
    {
      if ($result['pc_address'])
      {
        $address = OpenPNE2Util::t_decrypt($result['pc_address']);
        $this->conn->execute('INSERT INTO member_config (name, value, name_value_hash, member_id) VALUES ("pc_address", ?, ?, ?)', array($address, md5('pc_address,'.$address), $result['c_member_id']));
      }

      if ($result['ktai_address'])
      {
        $address = OpenPNE2Util::t_decrypt($result['ktai_address']);
        $this->conn->execute('INSERT INTO member_config (name, value, name_value_hash, member_id) VALUES ("mobile_address", ?, ?, ?)', array($address, md5('mobile_address,'.$address), $result['c_member_id']));
      }
    }
  }
}
