<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opPluginSyncTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opPlugin';
    $this->name             = 'sync';

    $this->addOptions(array(
      new sfCommandOption('target', null, sfCommandOption::PARAMETER_OPTIONAL, 'The target of sync'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->briefDescription = 'Synchronize bandled plugins';
    $this->detailedDescription = <<<EOF
The [opPlugin:sync|INFO] task synchronizes all bandled plugins.
Call it with:

  [php symfony opPlugin:sync|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    require sfConfig::get('sf_data_dir').'/version.php';
    
    sfToolkit::addIncludePath(array(sfConfig::get('sf_lib_dir').'/vendor/'));

    $pluginList = $this->getPluginList();
    foreach ($pluginList as $name => $info)
    {
      if ($options['target'] && $name !== $options['target'])
      {
        continue;
      }

      if (!preg_match('/^op[a-zA-Z0-9_\-]+Plugin$/', $name))
      {
        continue;
      }

      if (isset($info['install']) && false === $info['install'])
      {
        continue;
      }

      $option = array();
      if (isset($info['version']))
      {
        $option[] = '--release='.$info['version'];
      }
      if (isset($info['channel']))
      {
        $option[] = '--channel='.$info['channel'];
      }
      try
      {
        $task = new opPluginInstallTask($this->dispatcher, $this->formatter);
        $task->run(array('name' => $name), $option);
      }
      catch (sfCommandException $e)
      {
        $str = "Failed install";
        $this->logBlock($str, 'ERROR');
      }
    }
  }

  protected function getPluginList()
  {
    $list = array();

    $config = null;

    if ($proxy = parse_url(sfConfig::get('op_http_proxy')))
    {
      $config = array('adapter' => 'Zend_Http_Client_Adapter_Proxy');

      if (isset($proxy['host']))
      {
        $config['proxy_host'] = $proxy['host'];
      }

      if (isset($proxy['port']))
      {
        $config['proxy_port'] = $proxy['port'];
      }

      if (isset($proxy['user']))
      {
        $config['proxy_user'] = $proxy['user'];
      }

      if (isset($proxy['pass']))
      {
        $config['proxy_pass'] = $proxy['pass'];
      }
    }

    try
    {
      $client = new Zend_Http_Client(opPluginManager::getPluginListBaseUrl().OPENPNE_VERSION.'.yml', $config);
      $response = $client->request();

      if ($response->isSuccessful())
      {
        $list = sfYaml::load($response->getBody());
        $list = $this->applyLocalPluginList($list);
      }
      else
      {
        $str = "Failed to download plugin list.";
        $this->logBlock($str, 'ERROR');
      }
    }
    catch (Zend_Http_Client_Adapter_Exception $e)
    {
      $str = "Failed to download plugins list.";
      $this->logBlock($str, 'ERROR');
    }

    return $list;
  }

  protected function applyLocalPluginList($pluginList)
  {
    $path = sfConfig::get('sf_config_dir').'/plugins.yml';
    if (!is_readable($path))
    {
      return $pluginList;
    }

    $mergedList = array();
    $localList = (array)sfYaml::load($path);

    $default = array();
    if (isset($localList['all']))
    {
      $default = $localList['all'];
      unset($localList['all']);
    }

    foreach ($pluginList as $key => $value)
    {
      if (array_key_exists($key, $localList))
      {
        $mergedList[$key] = sfToolkit::arrayDeepMerge($value, (array)$localList[$key]);
      }
      else
      {
        $mergedList[$key] = sfToolkit::arrayDeepMerge($value, (array)$default);
      }
    }

    foreach ($localList as $key => $value)
    {
      if (!isset($mergedList[$key]))
      {
        $mergedList[$key] = $value;
      }
    }

    return $mergedList;
  }
}
