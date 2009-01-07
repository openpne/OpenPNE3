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

class opPluginUninstallTask extends sfPluginUninstallTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', 'plugins.openpne.jp'),
      new sfCommandOption('install_deps', 'd', sfCommandOption::PARAMETER_NONE, 'Whether to force installation of dependencies', null),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'uninstall';
    $this->briefDescription = 'Uninstalls the OpenPNE plugin';
    $this->detailedDescription = <<<EOF
The [opPlugin:uninstall|INFO] task uninstalls the OpenPNE plugin.
Call it with:

  [./symfony opPlugin:uninstall opSamplePlugin|INFO]
EOF;
  }
}
