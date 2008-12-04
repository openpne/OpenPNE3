<?php

class opPluginUploadTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'upload';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [opPlugin:upload|INFO] task does things.
Call it with:

  [./symfony opPlugin:upload|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $pluginName = $arguments['name'];
    $configuration = $this->createConfiguration('pc_backend', 'cli');

    $pluginInfo = $this->getPluginManager()->getPluginInfo($pluginName);
    if (!$pluginInfo)
    {
      throw new sfException(sprintf('Plugin "%s" is not registered in %s.', $pluginName, opPluginManager::OPENPNE_PLUGIN_CHANNEL));
    }

    $packagePath = sfConfig::get('sf_plugins_dir').'/'.$pluginName;
    if (!is_readable($packagePath.'/package.xml'))
    {
      throw new sfException(sprintf('Plugin "%s" dosen\'t have a definition file.', $pluginName));
    }

    $infoXml = simplexml_load_file($packagePath.'/package.xml');
    $filepath = sfConfig::get('sf_cache_dir').'/'.sprintf('%s-%s.tgz', (string)$infoXml->name, (string)$infoXml->version->release);
    if (!is_readable($filepath))
    {
      throw new sfException(sprintf('Please run opPlugin:archive task to create archive.', $pluginName));
    }

    $url = $this->getPluginManager()->getBaseURL().'r/'.$pluginName.'/upload';
    $url = str_replace('feeds', 'feeds_dev', $url);
    $browser = new sfWebBrowser();

    $params = array(
      'file'      => $filepath,
      'stability' => (string)$infoXml->stability->release,
      'version'   => (string)$infoXml->version->release,
      'note'      => (string)$infoXml->notes
    );

    $headers = array(
      'Authorization' => 'Basic '.base64_encode('ebihara:password'),
    );

    $browser->post($url, $params, $headers);
    if ($browser->responseIsError())
    {
      $this->logBlock('Task can\'t upload the plugin.', 'ERROR');
    }
    else
    {
      $this->logBlock('The plugin have been successfully uploaded.', 'INFO');
    }
  }

  public function getPluginManager()
  {
    if (is_null($this->pluginManager))
    {
      $this->pluginManager = new opPluginManager($this->dispatcher);
    }

    return $this->pluginManager;
  }
}
