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

class opPluginDeactivateTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'deactivate';
    $this->briefDescription = 'Deactivates the installed plugin.';
    $this->detailedDescription = <<<EOF
The [opPlugin:deactivate|INFO] task deactivates the installed plugin.
Call it with:

  [./symfony opPlugin:deactivate opSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $configuration = $this->createConfiguration('pc_frontend', 'cli');
    $name = $arguments['name'];

    if (!$configuration->isPluginExists($name))
    {
      throw new sfException(sprintf('Plugin "%s" does not installed', $name));
    }

    if ($configuration->isDisabledPlugin($name))
    {
      throw new sfException(sprintf('Plugin "%s" is already disactivated', $name));
    }

    opPlugin::getInstance($name)->setIsActive(false);

    $cc = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $cc->run();
  }
}
