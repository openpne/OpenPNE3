<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once 'PEAR/PackageFileManager2.php';

class opPluginDefineTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
      new sfCommandArgument('version', sfCommandArgument::REQUIRED, 'The plugin version'),
      new sfCommandArgument('stability', sfCommandArgument::REQUIRED, 'The plugin stability'),
      new sfCommandArgument('note', sfCommandArgument::REQUIRED, 'The plugin release note'),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'define';
    $this->briefDescription = 'Creates the plugin definition file "package.xml"';
    $this->detailedDescription = <<<EOF
The [opPlugin:define|INFO] task creates the plugin definition file "package.xml".
Call it with:

  [./symfony opPlugin:define opSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $pluginName = $arguments['name'];

    $info = $this->getPluginManager()->getPluginInfo($pluginName);
    if (!$info)
    {
      $info = array(
        'n' => $pluginName,
        'c' => opPluginManager::OPENPNE_PLUGIN_CHANNEL,
        'l' => 'Apache 2.0',
        's' => ' ',
        'd' => ' ',
      );
    }

    $maintainers = $this->getPluginManager()->getPluginMaintainer($pluginName);
    $maintainer = $this->getPluginManager()->getMaintainerInfo($maintainers['m']['h']);

    $packageXml = new PEAR_PackageFileManager2();
    $options = array(
      'packagedirectory'  => sfConfig::get('sf_plugins_dir').'/'.$pluginName.'/',
      'filelistgenerator' => 'file',
      'baseinstalldir'    => '/',
    );
    $e = $packageXml->setOptions($options);
    if (PEAR::isError($e))
    {
      echo $e->getMessage();
      exit;
    }

    $packageXml->setPackage($pluginName);
    $packageXml->setChannel(opPluginManager::OPENPNE_PLUGIN_CHANNEL);
    $packageXml->setReleaseVersion($arguments['version']);
    $packageXml->setReleaseStability($arguments['stability']);
    $packageXml->setApiVersion($arguments['version']);
    $packageXml->setApiStability($arguments['stability']);
    $packageXml->addMaintainer('lead', $maintainer['h'], $maintainer['n'], 'maintainer@example.com');
    $packageXml->setNotes($arguments['note']);
    $packageXml->setPhpDep('5.2.0');
    $packageXml->setPearinstallerDep('1.4.0');
    $packageXml->addPackageDepWithChannel('package', 'symfony', 'pear.symfony-project.com', '1.2.0');
    $packageXml->generateContents();
    $packageXml->setPackageType('php');

    if (isset($info['l']))
    {
      $packageXml->setLicense($info['l']);
    }
    if (isset($info['s']))
    {
      $packageXml->setSummary($info['s']);
    }
    if (isset($info['d']))
    {
      $packageXml->setDescription($info['d']);
    }

    $e = $packageXml->writePackageFile();
    if (PEAR::isError($e))
    {
      echo $e->getMessage();
      exit;
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
