<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy fixes revision for migration.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30FixRevisionStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $this->getDatabaseManager();

    Doctrine::getTable('SnsConfig')->set('OpenPNE_revision', 29);
    Doctrine::getTable('SnsConfig')->set('opAshiatoPlugin_revision', 2);
    Doctrine::getTable('SnsConfig')->set('opCommunityTopicPlugin_revision', 4);
    Doctrine::getTable('SnsConfig')->set('opOpenSocialPlugin_revision', 4);
    Doctrine::getTable('SnsConfig')->set('opMessagePlugin_revision', 4);
    Doctrine::getTable('SnsConfig')->set('opDiaryPlugin', 3);

    Doctrine::getTable('SnsConfig')->set('opAuthMobileUIDPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opAuthOpenIDPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opCommunityTopicPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opWebAPIPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opDiaryPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opBlogPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opOpenSocialPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opAshiatoPlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opMessagePlugin_needs_data_load', 0);
    Doctrine::getTable('SnsConfig')->set('opAlbumPlugin_needs_data_load', 0);
  }
}

