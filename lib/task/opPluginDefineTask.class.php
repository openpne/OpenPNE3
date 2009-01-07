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
      throw new sfException(sprintf('Plugin "%s" is not registered in %s.', $pluginName, opPluginManager::OPENPNE_PLUGIN_CHANNEL));
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
