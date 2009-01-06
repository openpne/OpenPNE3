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
 * HomeWidgetConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class HomeWidgetConfigForm extends OpenPNEFormAutoGenerate
{
  protected $homeWidget;

  public function __construct(HomeWidget $homeWidget, $options = array(), $CSRFSecret = null)
  {
    $this->homeWidget = $homeWidget;

    parent::__construct(array(), $options, $CSRFSecret);

    $config = sfConfig::get('op_widget_list', array());
    if (empty($config[$homeWidget->getName()]['config']))
    {
      throw new RuntimeException('The widget has not registered or it doesn\'t have any configuration items.');
    }

    $widgetConfig = $config[$homeWidget->getName()]['config'];
    foreach ($widgetConfig as $key => $value)
    {
      $this->setWidget($key, $this->generateWidget($value));
      $this->setValidator($key, $this->generateValidator($value));

      $config = HomeWidgetConfigPeer::retrieveByWidgetIdAndName($homeWidget->getId(), $key);
      if ($config)
      {
        $this->setDefault($key, $config->getValue());
      }
    }

    $this->widgetSchema->setNameFormat('home_widget_config[%s]');
  }

  public function save()
  {
    foreach ($this->values as $key => $value)
    {
      $homeWidgetConfig = HomeWidgetConfigPeer::retrieveByWidgetIdAndName($this->homeWidget->getId(), $key);
      if (!$homeWidgetConfig)
      {
        $homeWidgetConfig = new HomeWidgetConfig();
        $homeWidgetConfig->setHomeWidget($this->homeWidget);
        $homeWidgetConfig->setName($key);
      }
      $homeWidgetConfig->setValue($value);
      $homeWidgetConfig->save();
    }
  }
}
