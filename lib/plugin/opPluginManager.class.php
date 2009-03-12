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

  public function retrieveChannelXml($path)
  {
    $rest = $this->getEnvironment()->getRest();
    return $rest->_rest->retrieveXml($this->getBaseURL().$path);
  }

  public function getPluginInfo($plugin)
  {
    $data = $this->retrieveChannelXml('p/'.strtolower($plugin).'/info.xml');
    $result = array_merge(array(
        'n' => $plugin,
        'c' => opPluginManager::OPENPNE_PLUGIN_CHANNEL,
        'l' => 'Apache',
        's' => $plugin,
        'd' => $plugin,
    ), (array)$data);

    return $result;
  }

  public function getPluginMaintainer($plugin)
  {
    $data = $this->retrieveChannelXml('p/'.strtolower($plugin).'/maintainers2.xml');
    $result = array();

    foreach ($data->m as $maintainer)
    {
      $info = $this->retrieveChannelXml('m/'.strtolower((string)$maintainer->h).'/info.xml');
      $result[] = array_merge((array)$maintainer, array('n' => (string)$info->n));
    }

    return $result;
  }

  public function isExistsPlugin($plugin)
  {
    return (bool)$this->getPluginInfo($plugin);
  }
}
