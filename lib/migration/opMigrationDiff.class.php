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
}
