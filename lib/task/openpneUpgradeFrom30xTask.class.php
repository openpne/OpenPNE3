<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneUpgradeFrom30xTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class openpneUpgradeFrom30xTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'upgrade-from-30x';

    $this->addOptions(array(
      new sfCommandOption('rules', null, sfCommandOption::PARAMETER_REQUIRED | sfCommandOption::IS_ARRAY, 'The rules that you want to do'),
    ));

    $this->briefDescription = 'Upgrading from 3.0.x to current version';
    $this->detailedDescription = <<<EOF
The [openpne:upgrade-from-30x|INFO] task upgrades from 3.0.x to current version.
Call it with:

  [./symfony openpne:upgrade-from-30x|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $path = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'upgrade'.DIRECTORY_SEPARATOR.'30x';
    $upgrader = new opUpgrader($this->dispatcher, $this->formatter, $path, $this->configuration);
    if ($options['rules'])
    {
      $upgrader->setOption('targets', $options['rules']);
    }

    $this->logSection('upgrade', 'Begin upgrading from 3.0.x');
    $upgrader->execute();

    $task = new sfPluginPublishAssetsTask($this->dispatcher, $this->formatter);
    $task->run(array(), array());
  }
}
