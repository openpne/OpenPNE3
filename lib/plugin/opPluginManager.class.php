<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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
