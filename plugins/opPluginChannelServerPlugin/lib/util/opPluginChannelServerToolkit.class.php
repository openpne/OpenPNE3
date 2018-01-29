<?php

/**
* Copyright 2010 Kousuke Ebihara
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

/**
 * opPluginChannelServerToolkit
 *
 * @package    opPluginChannelServerPlugin
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginChannelServerToolkit
{
  public static function registerPearChannel(PEAR_ChannelFile $channel, $cacheDir = null)
  {
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    require_once 'PEAR.php';
    require_once 'PEAR/Common.php';
    require_once 'PEAR/ChannelFile.php';

    if (null === $cacheDir)
    {
      $cacheDir = sfConfig::get('sf_cache_dir');
    }

    $registry = new PEAR_Registry($cacheDir, $channel);

    $pear = new PEAR_Common();
    $pear->config->setRegistry($registry);

    if (!$registry->channelExists($channel->getName()))
    {
      $registry->addChannel($channel);
    }
    else
    {
      $registry->updateChannel($channel);
    }

    return $pear;
  }

  public static function generatePearChannelFile($channelName, $summary, $alias, $baseUrl)
  {
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    require_once 'PEAR.php';
    require_once 'PEAR/Common.php';
    require_once 'PEAR/ChannelFile.php';

    $channel = new PEAR_ChannelFile();
    $channel->setName($channelName);
    $channel->setSummary($summary);
    $channel->setAlias($alias);
    $channel->setBaseURL('REST1.0', $baseUrl);
    $channel->setBaseURL('REST1.1', $baseUrl);
    $channel->setBaseURL('REST1.2', $baseUrl);
    $channel->setBaseURL('REST1.3', $baseUrl);

    return $channel;
  }

  public static function getConfig($name, $default = null)
  {
    return Doctrine::getTable('SnsConfig')->get(opPluginChannelServerPluginConfiguration::CONFIG_KEY_PREFIX.$name, $default);
  }

  public static function generateTarByPluginDir(array $info, $filename, $input, $output)
  {
    $timeLimit = ini_get('max_execution_time');
    set_time_limit(0);

    require_once 'Archive/Tar.php';

    $tar = new Archive_Tar($output.'/'.$filename, true);
    foreach ($info['filelist'] as $file => $data)
    {
      $tar->addString($info['name'].'-'.$info['version'].'/'.$file, file_get_contents($input.'/'.$file));
    }
    $tar->addString('package.xml', file_get_contents($input.'/package.xml'));

    set_time_limit($timeLimit);
  }
}
