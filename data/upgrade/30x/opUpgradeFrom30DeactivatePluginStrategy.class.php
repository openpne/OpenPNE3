<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy deactivates plugins.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30DeactivatePluginStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    opPlugin::getInstance('opFavoritePlugin', $this->options['dispatcher'])->setIsActive(false);
    opPlugin::getInstance('opIntroFriendPlugin', $this->options['dispatcher'])->setIsActive(false);
    opPlugin::getInstance('opRankingPlugin', $this->options['dispatcher'])->setIsActive(false);
  }
}

