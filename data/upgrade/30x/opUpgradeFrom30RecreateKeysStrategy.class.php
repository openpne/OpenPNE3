<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy recreates keys.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30RecreateKeysStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $this->getDatabaseManager();
    $conn = Doctrine_Manager::connection();

    foreach ($conn->import->listTables() as $table)
    {
      if (strpos($table, '_translation'))
      {
        continue;
      }

      if (in_array($table, $this->getOption('ignores', array())))
      {
        continue;
      }

      $modelName = Doctrine_Inflector::classify($table);
      if ('Message' === $modelName)
      {
        $modelName = 'SendMessageData';
      }

      $constraints = $conn->import->listTableConstraints($table);
      array_shift($constraints);  // first constraint is PRIMARY KEY

      if ($constraints)
      {
        foreach ($constraints as $constraint)
        {
          if (strpos($constraint, '_U_')) // it is unique index
          {
            $this->dropRawIndex($conn, $table, $constraint);
          }
        }
      }

      $indexes = $conn->import->listTableIndexes($table);
      if ($indexes)
      {
        foreach ($indexes as $index)
        {
          // foreign key
          if (strpos($index, '_FI_'))
          {
            $fk = str_replace('_FI_', '_FK_', $index);
            $conn->export->dropForeignKey($table, $fk);
          }

          $this->dropRawIndex($conn, $table, $index);
        }
      }

      $exportedTable = Doctrine::getTable($modelName)->getExportableFormat();
      $newIndexes = $exportedTable['options']['indexes'];
      $newForeignKeys = $exportedTable['options']['foreignKeys'];

      foreach ($newIndexes as $name => $definition)
      {
        $definition['fields'] = (array)$definition['fields'];
        $conn->export->createIndex($table, $name, $definition);
      }

      foreach ($newForeignKeys as $name => $definition)
      {
        $definition['fields'] = (array)$definition['fields'];
        $conn->export->createForeignKey($table, $definition);
      }
    }
  }

  protected function dropRawIndex($conn, $table, $index)
  {
    $sql = str_replace('_idx', '', $conn->export->dropIndexSql($table, $index));
    $conn->execute($sql);
  }
}

