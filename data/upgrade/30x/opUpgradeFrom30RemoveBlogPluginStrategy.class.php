<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy removes files of blog plugin because the plugin wasn't managed by plugin channel server.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30RemoveBlogPluginStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $path = sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR.'opBlogPlugin';

    $finder = sfFinder::type('any');
    $filesystem = new sfFilesystem($this->options['dispatcher'], $this->options['formatter']);
    $filesystem->remove($finder->in($path));
    $filesystem->remove($path);
  }
}

