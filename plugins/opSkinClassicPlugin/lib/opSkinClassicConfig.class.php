<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opSkinClassicConfig
{
  const PLUGIN_NAME = 'opSkinClassicPlugin';
  const DEFAULT_THEME = '005_openpne_blue';

  static protected
    $currentTheme = '',
    $defaults = array(),

    $allowedColors = array(
      'line_color', 'page_background_color', 'contents_background_color',
      'frame_color', 'header_background_color', 'information_background_color',
      'box_background_color', 'left_menu_background_color',
    ),

    $themeImages = array(
      'skin_after_header', 'skin_after_header_2', 'skin_before_header', 'skin_footer',
      'skin_login', 'skin_login_open', 'skin_before_header', 'skin_navi_c', 'skin_navi_c_2',
      'skin_navi_f', 'skin_navi_f_2', 'skin_navi_h', 'skin_navi_h_2',
    ),

    $images = array(
      'icon_arrow_1', 'bg_button', 'bg_button_a', 'content_header_1', 'icon_title_1', 'icon_arrow_2',
      'colon', 'icon_information', 'marker', 'articleList_marker', 'icon_3', 'icon_1', 'icon_2',
    );

  static public function get($name)
  {
    $result = Doctrine::getTable('SkinConfig')->get(self::PLUGIN_NAME, $name, self::getDefault($name));

    if ($result !== self::getDefault($name) && (strlen($name) - strlen('_image')) === strrpos($name, '_image'))
    {
      $result = '../cache/img/gif/w_h/'.$result.'.gif';
    }

    return $result;
  }

  static public function set($name, $value)
  {
    return Doctrine::getTable('SkinConfig')->set(self::PLUGIN_NAME, $name, $value);
  }

  static public function delete($name)
  {
    $config = Doctrine::getTable('SkinConfig')->retrieveByPluginAndName(self::PLUGIN_NAME, $name);
    if ($config)
    {
      $config->delete();
    }
  }

  static public function setCurrentTheme($theme)
  {
    self::$currentTheme = $theme;
  }

  static public function getCurrentTheme()
  {
    if (empty(self::$currentTheme))
    {
      self::$currentTheme = Doctrine::getTable('SkinConfig')->get(self::PLUGIN_NAME, 'theme', self::DEFAULT_THEME);
    }

    return self::$currentTheme;
  }

  static public function getAllowdColors()
  {
    return self::$allowedColors;
  }

  static public function getImages()
  {
    return self::$images;
  }

  static public function getThemeImages()
  {
    return self::$themeImages;
  }

  static public function getDefault($name)
  {
    $theme = self::getCurrentTheme();

    if (empty(self::$defaults[$theme]))
    {
      self::$defaults = self::getDefaults();

      foreach (self::$themeImages as $v)
      {
        self::$defaults[$theme][$v.'_image'] = '../../opSkinClassicPlugin/images/'.$theme.'/'.$v.'.jpg';
      }

      foreach (self::$images as $v)
      {
        self::$defaults[$theme][$v.'_image'] = '../../opSkinClassicPlugin/images/'.$v.'.gif';
      }

      self::$defaults[$theme]['skin_footer_image'] = '../../opSkinClassicPlugin/images/skin_footer.jpg';
    }

    if (!empty(self::$defaults[$theme][$name]))
    {
      return self::$defaults[$theme][$name];
    }

    return null;
  }

  static public function getDefaults()
  {
    if (empty(self::$defaults))
    {
      $configPath = realpath(dirname(__FILE__).'/../config/preset.yml');
      self::$defaults = sfYaml::load($configPath);
    }

    return self::$defaults;
  }
}
