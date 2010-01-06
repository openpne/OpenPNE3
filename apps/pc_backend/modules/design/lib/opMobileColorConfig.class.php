<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMobileColorConfigForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMobileColorConfigForm extends sfForm
{
  protected $colorList = array(
    'core_color_1'  => 'Page Background',
    'core_color_2'  => 'Title Background',
    'core_color_3'  => 'Subtitle Background',
    'core_color_4'  => 'Home Description Background',
    'core_color_5'  => 'List A Head Background',
    'core_color_6'  => 'List A Background1',
    'core_color_7'  => 'List A Background2',
    'core_color_8'  => 'List B Head Background',
    'core_color_9'  => 'List B Background1',
    'core_color_10' => 'List B Background2',
    'core_color_11' => 'Break Border',
    'core_color_12' => 'List A Border',
    'core_color_13' => 'List B Border',
    'core_color_14' => 'Page Text',
    'core_color_15' => 'Link Text',
    'core_color_23' => 'Link Text (Active)',
    'core_color_17' => 'Link Text (Visited)',
    'core_color_18' => 'Title Text',
    'core_color_19' => 'Date / Item Text',
    'core_color_20' => '⇒ Text',
    'core_color_21' => '▼ Text',
    'core_color_22' => 'Error Text',
    'core_color_24' => 'Subtitle Text',
    'core_color_25' => 'List A Head Text',
    'core_color_26' => 'List B Head Text',
    'core_color_27' => 'Search Head Background',
    'core_color_28' => 'Search Head Text',
  );

  public function configure()
  {
    foreach ($this->colorList as $k => $v)
    {
      $this->setWidget($k, new opWidgetFormInputColor(array('is_display_pre_color' => true)));
      $this->setValidator($k, new opValidatorColor());
      $this->widgetSchema->setLabel($k, $v);
      $this->widgetSchema->setDefault($k, opColorConfig::get($k, null, 'mobile_frontend'));
    }

    $this->widgetSchema->setNameFormat('color[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $k => $v)
    {
      opColorConfig::set($k, $v, 'mobile_frontend');
    }
  }
}
