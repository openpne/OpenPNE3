<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing sns term.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2SnsTermStrategy extends opUpgradeAbstractStrategy
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

  protected function doRun()
  {
    // insert initial data
    $this->dataLoad(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'010_import_sns_terms.yml');

    $list = array(
      'friend'    => 'WORD_FRIEND',
      'my_friend' => 'WORD_MY_FRIEND',
      'community' => 'WORD_COMMUNITY',
      'nickname'  => 'WORD_NICKNAME',
    );

    $sql = 'UPDATE sns_term_translation, c_admin_config SET sns_term_translation.value = c_admin_config.value'
         . ' WHERE sns_term_translation.id = ? AND c_admin_config.name = ? AND sns_term_translation.lang = ?';
    foreach ($list as $key => $value)
    {
      $pcId = $this->conn->fetchOne('SELECT id FROM sns_term WHERE name = ? AND application = ?', array($key, 'pc_frontend'));
      $mobileId = $this->conn->fetchOne('SELECT id FROM sns_term WHERE name = ? AND application = ?', array($key, 'mobile_frontend'));

      if ($pcId)
      {
        $this->conn->execute($sql, array($pcId, $value, 'ja_JP'));
      }

      if ($mobileId)
      {
        $this->conn->execute($sql, array($mobileId, $value.'_HALF', 'ja_JP'));
      }
    }
  }
}
