<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy creates the specified tables.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeCreateTableStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $this->getDatabaseManager();

    foreach ($this->getQueries() as $query)
    {
      $db = $this->getDatabaseManager()->getDatabase('doctrine');
      $db->getDoctrineConnection()->execute($query);
    }
  }

  public function getQueries()
  {
    $result = array();

    $models = $this->getOption('models');
    if (!empty($models))
    {
      $queries = Doctrine_Manager::connection()->export->exportSortedClassesSql($models);
      if (isset($queries['doctrine']))
      {
        $result = $queries['doctrine'];
      }
    }

    return $result;
  }
}

