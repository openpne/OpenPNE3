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

class HomeWidget extends BaseHomeWidget
{
  public function save(PropelPDO $con = null)
  {
    if (!$this->getSortOrder())
    {
      $maxSortOrder = 0;

      $widgets = HomeWidgetPeer::retrieveByType($this->getType());
      $finalWidget = array_pop($widgets);
      if ($finalWidget)
      {
        $maxSortOrder = $finalWidget->getSortOrder();
      }

      $this->setSortOrder($maxSortOrder + 10);
    }

    return parent::save($con);
  }

  public function getComponentModule()
  {
    $list = sfConfig::get('op_widget_list');

    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return $list[$this->getName()]['component'][0];
  }

  public function getComponentAction()
  {
    $list = sfConfig::get('op_widget_list');

    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return $list[$this->getName()]['component'][1];
  }

  public function isEnabled()
  {
    $list = sfConfig::get('op_widget_list');
    if (empty($list[$this->getName()]))
    {
      return false;
    }

    return true;
  }

  public function getConfig($name)
  {
    $result = null;
    $list = sfConfig::get('op_widget_list');

    $config = HomeWidgetConfigPeer::retrieveByWidgetIdAndName($this->getId(), $name);
    if ($config)
    {
      $result = $config->getValue();
    }
    elseif (isset($list[$this->getName()]['config'][$name]['Default']))
    {
      $result = $list[$this->getName()]['config'][$name]['Default'];
    }

    return $result;
  }
}
