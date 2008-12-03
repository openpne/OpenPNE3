<?php

/**
 * opPluginManager allows you to manage OpenPNE plugins installation and uninstallation.
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

      $environment->registerChannel(self::OPENPNE_PLUGIN_CHANNEL, true);
    }

    parent::__construct($dispatcher, $environment);
  }

  public function getChannel()
  {
    return $this->getEnvironment()->getRegistry()->getChannel(self::OPENPNE_PLUGIN_CHANNEL);
  }

  public function retrieveChannelData($path)
  {
    $rest = $this->getEnvironment()->getRest();
    $baseUrl = $this->getChannel()->getBaseURL('REST1.1');
    return $rest->_rest->retrieveData($baseUrl.$path);
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
