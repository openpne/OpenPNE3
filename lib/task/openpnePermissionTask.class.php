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
  protected
    $opFailed  = array();

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
    $this->chmod($webCacheDir, 0777);

    // note those files that failed
    if (count($this->opFailed))
    {
      if ('prod' === $options['env'])
      {
        $this->logBlock(array(
          'Permissions on some files could not be fixed.',
          'You may fix this problem for accessing "/pc_backend.php/sns/cache" via your web browser.',
          '',
          'If you want to get more information, please execute "./symfony openpne:permission --env=dev".'
        ), 'INFO_LARGE');
      }
      else
      {
        $this->logBlock(array_merge(
          array('Permissions on the following file(s) could not be fixed:', ''),
          array_map(create_function('$f', 'return \' - \'.sfDebug::shortenFilePath($f);'), $this->opFailed)
        ), 'ERROR_LARGE');
      }
    }
  }

  public function handleError($no, $string, $file, $line, $context)
  {
    $this->opFailed[] = $this->current;
  }
}
