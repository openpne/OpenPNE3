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
 * opInstalledPluginManager allows you to manage installed OpenPNE plugins.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opInstalledPluginManager
{
  public function getInstalledPlugins()
  {
    $result = array();

    $plugins = sfContext::getInstance()->getConfiguration()->getAllOpenPNEPlugins();
    foreach ($plugins as $pluginName)
    {
      $result[$pluginName] = $this->getPluginInstance($pluginName);
    }

    return $result;
  }

  public function getPluginInstance($plugin)
  {
    return opPlugin::getInstance($plugin);
  }
}
