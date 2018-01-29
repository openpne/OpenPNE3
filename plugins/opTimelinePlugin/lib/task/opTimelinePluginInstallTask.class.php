<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opTimelinePluginInstallTask
 *
 * @package    opTimelinePlugin
 * @subpackage opTimelinePlugin
 * @author     Shouta Kashiwagi <kashiwagi@tejimaya.com>
 */

class opTimelinePluginInstallTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opTimelinePlugin';
    $this->name             = 'install';
    $this->briefDescription = 'Install Command for "opTimelinePlugin".';

    $this->detailedDescription = <<<EOF
Use this command to install "opTimelinePlugin".
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // execute ./symfony doctrine:data-load
    $sfDoctrineDataLoadTask = new sfDoctrineDataLoadTask($this->dispatcher, $this->formatter); 
    $sfDoctrineDataLoadTask->run($arguments = array('./plugins/opTimelinePlugin/data/fixtures/010_gadget_setting.yml'), $options = array('append'));

    // execute ./symfony plugin:publish-assets
    $sfPluginPublishAssetsTask = new sfPluginPublishAssetsTask($this->dispatcher, $this->formatter);
    $sfPluginPublishAssetsTask->run();

    // execute ./symfony cc
    $sfCacheClearTask = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $sfCacheClearTask->run($arguments = array(), $options = array('type' => 'all'));
  }
}
