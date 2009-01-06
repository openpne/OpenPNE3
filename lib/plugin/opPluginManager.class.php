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
 * opPluginManager allows you to manage OpenPNE plugins.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginManager extends sfSymfonyPluginManager
{
  const OPENPNE_PLUGIN_CHANNEL = 'plugins.openpne.jp';

  public function __construct(sfEventDispatcher $dispatcher, sfPearEnvironment $environment = null)
  {
    if (!$environment)
    {
      $environment = new sfPearEnvironment($dispatcher, array(
        'plugin_dir' => sfConfig::get('sf_plugins_dir'),
        'cache_dir' => sfConfig::get('sf_cache_dir').'/.pear',
        'web_dir' => sfConfig::get('sf_web_dir'),
        'rest_base_class' => 'opPearRest',
      ));

      try
      {
        $environment->registerChannel(self::OPENPNE_PLUGIN_CHANNEL, true);
      }
      catch (sfPluginException $e) {}
    }

    parent::__construct($dispatcher, $environment);
  }

  public function getChannel()
  {
    return $this->getEnvironment()->getRegistry()->getChannel(self::OPENPNE_PLUGIN_CHANNEL);
  }

  public function getBaseURL()
  {
    return $this->getChannel()->getBaseURL('REST1.1');
  }

  public function retrieveChannelData($path)
  {
    $rest = $this->getEnvironment()->getRest();
    return $rest->_rest->retrieveData($this->getBaseURL().$path);
  }

  public function getPluginInfo($plugin)
  {
    return $this->retrieveChannelData('p/'.strtolower($plugin).'/info.xml');
  }

  public function getPluginMaintainer($plugin)
  {
    return $this->retrieveChannelData('p/'.strtolower($plugin).'/maintainers.xml');
  }

  public function getMaintainerInfo($maintainer)
  {
    return $this->retrieveChannelData('m/'.strtolower($maintainer).'/info.xml');
  }

  public function isExistsPlugin($plugin)
  {
    return (bool)$this->getPluginInfo($plugin);
  }
}
