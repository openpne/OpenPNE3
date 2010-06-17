<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The upgrating strategy by importing yml.
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom2DataLoadStrategy extends opUpgradeDataLoadStrategy
{
  public function run()
  {
    $this->getDatabaseManager();

    $coreFixtureDir = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR;

    $paths = array(
      $coreFixtureDir.'000_revision.yml',
      $coreFixtureDir.'004_import_navi_menu.yml',
      $coreFixtureDir.'005_import_gadgets.yml',
      $coreFixtureDir.'006_import_mobile_navi_menu.yml',
      $coreFixtureDir.'008_import_backend_navi_menu.yml',
      $coreFixtureDir.'009_import_banner.yml',
    );

    $pluginDirs = sfFinder::type('dir')->name('data')->in(sfFinder::type('dir')->name('op*Plugin')->maxdepth(1)->in(sfConfig::get('sf_plugins_dir')));
    $fixtureDirs = sfFinder::type('dir')->name('fixtures')
      ->prune('migrations', 'upgrade')
      ->in($pluginDirs);
    $pluginFixtures = sfFinder::type('file')->name('*.yml')->in($fixtureDirs);

    $paths = array_merge($paths, $pluginFixtures);
    foreach ($paths as $path)
    {
      $this->dataLoad($path);
    }
  }
}
