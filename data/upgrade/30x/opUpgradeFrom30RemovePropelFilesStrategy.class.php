<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy removes files for propel.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30RemovePropelFilesStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $pluginDirs = sfFinder::type('directory')
      ->maxdepth(0)
      ->name('op*Plugin')
      ->in(sfConfig::get('sf_plugins_dir'));

    $filesystem = new sfFilesystem($this->options['dispatcher'], $this->options['formatter']);
    $finder = sfFinder::type('any');

    foreach ($pluginDirs as $dir)
    {
      $basePath = $dir.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'model';

      $path = $basePath.DIRECTORY_SEPARATOR.'map';
      if (is_dir($path))
      {
        $filesystem->remove($finder->in($path));
        $filesystem->remove($path);
      }

      $path = $basePath.DIRECTORY_SEPARATOR.'om';
      if (is_dir($path))
      {
        $filesystem->remove($finder->in($path));
        $filesystem->remove($path);
      }
    }
  }
}

