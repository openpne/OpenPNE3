<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMigrationDiff
 *
 * @package    OpenPNE
 * @subpackage migration
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opMigrationDiff extends Doctrine_Migration_Diff
{
  protected function _buildModelInformation(array $models)
  {
    $info = array();
    foreach ($models as $key => $model)
    {
      $classRef = new ReflectionClass($model);
      if ($classRef->isAbstract())
      {
        continue;
      }

      $table = Doctrine::getTable($model);
      if ($table->getTableName() !== $this->_migration->getTableName())
      {
        $info[$model] = $table->getExportableFormat();
        foreach ($table->getTemplates() as $name => $template)
        {
          $plugin = $template->getPlugin();
          if ($plugin)
          {
            $info[$plugin->getOption('className')] = $plugin->getTable()->getExportableFormat();
          }
        }
      }
    }

    $info = $this->_cleanModelInformation($info);

    return $info;
  }

  protected function _diff($from, $to)
  {
    $fromModels = Doctrine::initializeModels(Doctrine::loadModels($from, Doctrine::MODEL_LOADING_AGGRESSIVE));
    $toModels = Doctrine::initializeModels(Doctrine::loadModels($to, Doctrine::MODEL_LOADING_AGGRESSIVE));

    // Build schema information for the models
    $fromInfo = $this->_buildModelInformation($fromModels);
    $toInfo = $this->_buildModelInformation($toModels);

    $this->_decreaseInformations($fromInfo, $toInfo);

    // Build array of changes between the from and to information
    $changes = $this->_buildChanges($fromInfo, $toInfo);

    $this->_cleanup();

    return $changes;
  }

  protected function _decreaseInformations(&$fromInfo, &$toInfo)
  {
    $_changes = array_intersect_key($fromInfo, $toInfo);

    foreach ($_changes as $tableName => $tableInfo)
    {
      foreach ($tableInfo['columns'] as $columnName => $columnInfo)
      {
        if (!empty($fromInfo[$tableName]['columns'][$columnName])
            && !empty($toInfo[$tableName]['columns'][$columnName]))
        {
          $fromColumn =& $fromInfo[$tableName]['columns'][$columnName];
          $toColumn =& $toInfo[$tableName]['columns'][$columnName];

          if (('integer' === $fromColumn['type'] && '1' == $fromColumn['length'])
              || ('integer' === $toColumn['type'] && '1' == $toColumn['length']))
          {
            $fromColumn['type'] = 'boolean';
            $fromColumn['length'] = '1';
            $toColumn['type'] = 'boolean';
            $toColumn['length'] = '1';
          }

          $this->_removedSpecifiedEmptyParameter('unsigned', $fromColumn, $toColumn);
          $this->_removedSpecifiedEmptyParameter('fixed', $fromColumn, $toColumn);
          $this->_removedSpecifiedEmptyParameter('primary', $fromColumn, $toColumn);
          $this->_removedSpecifiedEmptyParameter('autoincrement', $fromColumn, $toColumn);
          $this->_removedSpecifiedEmptyParameter('notnull', $fromColumn, $toColumn);
          $this->_removedSpecifiedEmptyParameter('default', $fromColumn, $toColumn);
        }
      }
    }
  }

  protected function _removedSpecifiedEmptyParameter($key, &$array1, &$array2)
  {
    if ((isset($array1[$key]) || isset($array2[$key])) && !(isset($array1[$key]) && isset($array2[$key])))
    {
      if (empty($array1[$key]) && empty($array2[$key]))
      {
        $array1[$key] = null;
        $array2[$key] = null;
        unset($array1[$key], $array2[$key]);
      }
    }
  }
}
