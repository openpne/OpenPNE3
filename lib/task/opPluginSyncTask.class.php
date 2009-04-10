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

    $config = array(
      'adapter'  => 'Zend_Http_Client_Adapter_Proxy'
    );

    if ($proxy = parse_url(sfConfig::get('op_http_proxy')))
    {
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

    $pluginList = array();

    try
    {
      $client = new Zend_Http_Client('http://'.opPluginManager::OPENPNE_PLUGIN_CHANNEL.'/packages/'.OPENPNE_VERSION.'.yml', $config);
      $response = $client->request();

      if ($response->isSuccessful())
      {
        $pluginList = sfYaml::load($response->getBody());
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

    foreach ($pluginList as $name => $info)
    {
      if (!preg_match('/^op[a-zA-Z0-9_\-]+Plugin$/', $name))
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
}
