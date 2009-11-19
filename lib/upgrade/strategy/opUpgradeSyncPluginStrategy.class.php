<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy sync plugins.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeSyncPluginStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $task = new opPluginSyncTask($this->options['dispatcher'], $this->options['formatter']);
    $task->run();
  }
}

