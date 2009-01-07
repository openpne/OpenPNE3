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

class openpnePermissionTask extends sfProjectPermissionsTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'permission';
    $this->briefDescription = 'Fixes directory permissions for OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:permission|INFO] task fixes directory permissions for OpenPNE.
Call it with:

  [./symfony openpne:permission|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    parent::execute($arguments, $options);

    $webCacheDir = sfConfig::get('sf_web_dir').'/cache';
    $this->getFilesystem()->chmod($webCacheDir, 0777);
    $dirFinder = sfFinder::type('dir');
    $fileFinder = sfFinder::type('file');
    $this->getFilesystem()->chmod($dirFinder->in($webCacheDir), 0777);
    $this->getFilesystem()->chmod($fileFinder->in($webCacheDir), 0666);
  }
}
