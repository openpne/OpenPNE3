<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../../../lib/config/sfOpenPNEApplicationConfiguration.class.php';

class mobile_frontendConfiguration extends sfOpenPNEApplicationConfiguration
{
  public function configure()
  {
    // TODO: They must be managed by an original config handler
    $colors = array(
      'core_color_1'  => '#FFFFFF',
      'core_color_2'  => '#0D6DDF',
      'core_color_3'  => '#DDDDDD',
      'core_color_4'  => '#EEEEFF',
      'core_color_5'  => '#7DDADF',
      'core_color_6'  => '#E0EAEF',
      'core_color_7'  => '#FFFFFF',
      'core_color_8'  => '#C49FFF',
      'core_color_9'  => '#DCD1EF',
      'core_color_10' => '#FFFFFF',
      'core_color_11' => '#0D6DDF',
      'core_color_12' => '#B3CEEF',
      'core_color_13' => '#BFA4EF',
      'core_color_14' => '#000000',
      'core_color_15' => '#0000FF',
      'core_color_23' => '#FFFFFF',
      'core_color_17' => '#800080',
      'core_color_18' => '#EEEEEE',
      'core_color_19' => '#999966',
      'core_color_20' => '#0C5F0F',
      'core_color_21' => '#C49FFF',
      'core_color_22' => '#FF0000',
      'core_color_24' => '#000000',
      'core_color_25' => '#000000',
      'core_color_26' => '#000000',
      'core_color_27' => '#DDDDDD',
      'core_color_28' => '#000000',
    );

    $prefix = 'op_'.sfConfig::get('sf_app').'_color_config_';
    foreach ($colors as $key => $value)
    {
      sfConfig::set($prefix.$key, $value);
    }
  }

  public function initialize()
  {
    parent::initialize();

    if (!opMobileUserAgent::getInstance()->isCookie())
    {
      ini_set('session.use_only_cookies', 0);
      ini_set('session.use_cookies', 0);
      ini_set('session.use_trans_sid', 1);
    }

    sfWidgetFormSchema::setDefaultFormFormatterName('mobile');
  }
}
