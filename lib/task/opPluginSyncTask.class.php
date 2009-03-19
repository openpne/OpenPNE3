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

    $browser = new sfWebBrowser();
    $browser->get('http://'.opPluginManager::OPENPNE_PLUGIN_CHANNEL.'/packages/'.OPENPNE_VERSION.'.yml');
    if ($browser->responseIsError())
    {
      throw new sfException('Unable to retrieve the bandled packages list.');
    }

    $pluginList = sfYaml::load($browser->getResponseText());
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

      $task = new opPluginInstallTask($this->dispatcher, $this->formatter);
      $task->run(array('name' => $name), $option);
    }
  }
}
