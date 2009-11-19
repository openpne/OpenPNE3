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
class opUpgradeDataLoadStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $path = $this->options['dir'].DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.$this->options['name'].'.yml';
    if (!file_exists($path))
    {
      throw new RuntimeException('The specified yml doesn\'t exist.');
    }

    Doctrine::loadData($path, true);
  }
}
