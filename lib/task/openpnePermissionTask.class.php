<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpnePermissionTask extends sfProjectPermissionsTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'permission';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

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
    if (!is_dir($webCacheDir))
    {
      @$this->getFilesystem()->mkdirs($webCacheDir);
    }
    $this->getFilesystem()->chmod($webCacheDir, 0777);

    $dataDir = sfConfig::get('sf_data_dir').'/config';
    $fileFinder = sfFinder::type('file');
    $this->getFilesystem()->chmod($fileFinder->in($dataDir), 0666);
  }
}
