<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy create translation tables.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30CreateTranslationTableStrategy extends opUpgradeCreateTableStrategy
{
  public function getQueries()
  {
    $result = parent::getQueries();
    foreach ($result as $k => $v)
    {
      if (false === strpos($v, '_translation'))
      {
        unset($result[$k]);
      }
    }

    return $result;
  }
}

