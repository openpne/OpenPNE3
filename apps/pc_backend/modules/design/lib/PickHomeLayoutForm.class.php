<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Pick Home Layout Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PickHomeLayoutForm extends sfForm
{
  public $choices = array('layoutA', 'layoutB', 'layoutC');

  public function configure()
  {
    $default = 0;
    $snsConfig = SnsConfigPeer::retrieveByName('home_layout');
    if ($snsConfig)
    {
      $default = array_search($snsConfig->getValue(), $this->choices);
    }

    $this->setWidget('layout', new sfWidgetFormSelectPhotoRadio(array(
      'default'      => (int)$default,
      'class'        => 'layoutSelection',
      'choices'      => $this->choices,
      'image_prefix' => 'layout_selection_',
    )));

    $this->setValidator('layout', new sfValidatorChoice(array(
      'choices' => array_keys($this->choices),
    )));
    $this->widgetSchema->setNameFormat('pick_home_layout[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $snsConfig = SnsConfigPeer::retrieveByName('home_layout');
    if (!$snsConfig)
    {
      $snsConfig = new SnsConfig();
      $snsConfig->setName('home_layout');
    }
    $value = $this->choices[$this->values['layout']];
    $snsConfig->setValue($value);

    return (bool)$snsConfig->save();
  }
}
