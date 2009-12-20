<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing admin config.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2AdminConfigStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $this->getDatabaseManager();
    $this->conn = Doctrine_Manager::connection();

    $this->conn->beginTransaction();

    try
    {
      $this->importSnsConfig();
      $this->importSiteAdmin();

      $this->conn->commit();
    }
    catch (Exception $e)
    {
      $this->conn->rollback();

      throw $e;
    }
  }

  protected function importSnsConfig()
  {
    $list = array(
      'sns_name' => 'SNS_NAME',
      'sns_title' => 'SNS_TITLE',
      'admin_mail_address' => 'ADMIN_EMAIL',
      'enable_pc' => 'OPENPNE_ENABLE_PC',
      'enable_mobile' => 'OPENPNE_ENABLE_KTAI',
      'external_pc_login_url' => 'LOGIN_URL_PC',
      'external_mobile_login_url' => 'LOGIN_URL_KTAI',
      'enable_registration' => 'OPENPNE_REGIST_FROM',
      'retrieve_uid' => 'IS_GET_EASY_ACCESS_ID',
      'font_size' => 'OPENPNE_IS_SET_KTAI_FONT_SIZE',
    );

    foreach ($list as $k => $v)
    {
      if ('external_pc_login_url' === $k)
      {
        if ((bool)$this->conn->fetchOne('SELECT value FROM portal_config WHERE name = ?', array('USE_PORTAL')))
        {
          continue;
        }
      }

      if ('external_mobile_login_url' === $k)
      {
        if ((bool)$this->conn->fetchOne('SELECT value FROM portal_config WHERE name = ?', array('USE_PORTAL_KTAI')))
        {
          continue;
        }
      }

      $this->conn->execute('INSERT INTO sns_config (id, name, value) (SELECT NULL, ?, value FROM c_admin_config WHERE name = ? LIMIT 1)', array($k, $v));
    }
  }

  protected function importSiteAdmin()
  {
    $list = array(
      'customizing_css'  => 'inc_custom_css',
      'footer_after'     => 'inc_page_footer_after',
      'footer_before'    => 'inc_page_footer_before',
      'pc_html_head'     => 'inc_html_head',
      'pc_html_top'      => 'inc_page_top',
      'pc_html_top2'     => 'inc_page_top2',
      'pc_html_bottom'   => 'inc_page_bottom',
      'pc_html_bottom2'  => 'inc_page_bottom2',
      'mobile_html_head' => 'inc_ktai_html_head',
      'mobile_header'    => 'inc_ktai_header',
      'mobile_footer'    => 'inc_ktai_footer',
      'user_agreement'   => 'sns_kiyaku',
      'privacy_policy'   => 'sns_privacy',
    );

    foreach ($list as $k => $v)
    {
      $this->conn->execute('INSERT INTO sns_config (id, name, value) (SELECT NULL, ?, body FROM c_siteadmin WHERE target = ? LIMIT 1)', array($k, $v));
    }
  }
}
