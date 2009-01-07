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
 * Home Widget Add Form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class HomeWidgetAddForm extends sfForm
{
  public function configure()
  {
    $this->setValidator('top', new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    $this->setValidator('sideMenu', new sfValidatorCallback(array('callback' => array($this, 'validate'))));
    $this->setValidator('contents', new sfValidatorCallback(array('callback' => array($this, 'validate'))));

    $this->getWidgetSchema()->setNameFormat('new[%s]');
  }

  public function save()
  {
    foreach ($this->values as $type => $widgets)
    {
      if (!$widgets)
      {
        continue;
      }

      foreach ($widgets as $value)
      {
        $widget = new HomeWidget();
        $widget->setType($type);
        $widget->setName($value);
        $widget->save();
      }
    }
  }

  public function validate($validator, $value)
  {
    $result = array();

    foreach ($value as $key => $item)
    {
      if (array_key_exists($item, sfConfig::get('op_widget_list')))
      {
        $result[] = $item;
      }
    }

    return $result;
  }
}
