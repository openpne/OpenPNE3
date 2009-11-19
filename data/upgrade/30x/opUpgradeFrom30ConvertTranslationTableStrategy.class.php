<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy convert translation tables from 3.0x.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30ConvertTranslationTableStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $this->getDatabaseManager();
    $conn = Doctrine_Manager::connection();

    $tables = $this->getOption('tables');

    if (!empty($tables))
    {
      foreach ($tables as $table)
      {
        $oldName = $table.'_i18n';
        $newName = $table.'_translation';

        $oldColumns = array_keys($conn->import->listTableColumns($oldName));
        $newColumns = array_keys($conn->import->listTableColumns($newName));

        $commonColumns = array_intersect($newColumns, $oldColumns);

        $to = array_merge(array('lang'), $commonColumns);
        $from = array($oldName.'.culture');

        if (isset($newColumns['created_at']))
        {
          $to[] = 'created_at';
          $from[] = 'NOW()';
        }
        if (isset($newColumns['updated_at']))
        {
          $to[] = 'created_at';
          $from[] = 'NOW()';
        }

        foreach ($commonColumns as $v)
        {
          $from[] = $oldName.'.'.$v;
        }

        $sql = sprintf('INSERT INTO %s (%s) SELECT %s FROM %s;', $newName, implode(', ', $to), implode(', ', $from), $oldName);
        $conn->execute($sql);

        $conn->export->dropTable($oldName);
      }
    }
  }
}

