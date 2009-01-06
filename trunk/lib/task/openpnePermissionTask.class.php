<?php

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
