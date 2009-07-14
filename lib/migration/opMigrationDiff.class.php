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

    // Build array of changes between the from and to information
    $changes = $this->_buildChanges($fromInfo, $toInfo);

    $this->_cleanup();

    return $changes;
  }
}
