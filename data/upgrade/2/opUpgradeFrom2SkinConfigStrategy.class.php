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
class opUpgradeFrom2SkinConfigStrategy extends opUpgradeSQLImportStrategy
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
    opPlugin::getInstance('opSkinBasicPlugin', $this->options['dispatcher'])->setIsActive(false);
    opPlugin::getInstance('opSkinClassicPlugin', $this->options['dispatcher'])->setIsActive(true);

    $colorTable = array(
      'line_color' => 'color_5',
      'page_background_color' => 'color_16',
      'contents_background_color' => 'color_17',
      'frame_color' => 'color_4',
      'header_background_color' => 'color_10',
      'information_background_color' => 'color_13',
      'box_background_color' => 'color_6',
      'left_menu_background_color' => 'color_14',
    );

    foreach ($colorTable as $key => $value)
    {
      $this->conn->execute('INSERT INTO skin_config (plugin, name, value, created_at, updated_at) (SELECT "opSkinClassicPlugin", "'.$key.'", '.$value.', NOW(), NOW() FROM c_config_color WHERE c_config_color_id = ?)', array(1));
    }

    $this->conn->execute('INSERT INTO skin_config (plugin, name, value, created_at, updated_at) (SELECT "opSkinClassicPlugin", "theme", value, NOW(), NOW() FROM c_admin_config WHERE name = "OPENPNE_SKIN_THEME")');

    $images = array(
      'skin_after_header', 'skin_after_header_2', 'skin_before_header', 'skin_footer',
      'skin_login', 'skin_login_open', 'skin_before_header_2', 'skin_navi_c', 'skin_navi_c_2',
      'skin_navi_f', 'skin_navi_f_2', 'skin_navi_h', 'skin_navi_h_2',
      'icon_arrow_1', 'bg_button', 'content_header_1', 'icon_title_1', 'icon_arrow_2',
      'colon', 'icon_information', 'marker', 'articleList_marker', 'icon_3', 'icon_1', 'icon_2',
    );

    foreach ($images as $image)
    {
      $this->conn->execute('INSERT INTO skin_config (plugin, name, value, created_at, updated_at) (SELECT "opSkinClassicPlugin", "'.$image.'_image", filename, NOW(), NOW() FROM c_skin_filename WHERE skinname = ?)', array($image));
    }

    $this->conn->execute('DROP TABLE c_config_color');
  }
}
