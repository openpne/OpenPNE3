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

require_once 'Archive/Tar.php';

class opPluginArchiveTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
      new sfCommandArgument('dir', sfCommandArgument::OPTIONAL, 'The output dir', sfConfig::get('sf_cache_dir')),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'archive';
    $this->briefDescription = 'Creates the OpenPNE plugin archive.';
    $this->detailedDescription = <<<EOF
The [opPlugin:archive|INFO] task creates the OpenPNE plugin archive.
Call it with:

  [./symfony opPlugin:archive opSamplePlugin ~/Documents/myPlugins|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $pluginName = $arguments['name'];
    $packagePath = sfConfig::get('sf_plugins_dir').'/'.$pluginName;
    if (!is_readable($packagePath.'/package.xml'))
    {
      throw new sfException(sprintf('Plugin "%s" dosen\'t have a definition file.', $pluginName));
    }

    $infoXml = simplexml_load_file($packagePath.'/package.xml');
    $filename = sprintf('%s-%s.tgz', (string)$infoXml->name, (string)$infoXml->version->release);
    $dirPath = sfConfig::get('sf_plugins_dir').'/'.$pluginName;

    $tar = new Archive_Tar($arguments['dir'].'/'.$filename, true);
    foreach ($infoXml->contents->dir->file as $file)
    {
      $attributes = $file->attributes();
      $name = (string)$attributes['name'];
      $tar->addString($pluginName.'/'.$name, file_get_contents($dirPath.'/'.$name));
    }
    $tar->addString($pluginName.'/package.xml', file_get_contents($dirPath.'/package.xml'));
  }
}
