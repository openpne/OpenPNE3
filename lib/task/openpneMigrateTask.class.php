<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneMigrateTask extends sfPropelBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'migrate';

    require sfConfig::get('sf_data_dir').'/version.php';

    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::OPTIONAL, 'The plugin name or "OpenPNE"', 'OpenPNE'),
    ));

    $this->addOptions(array(
      new sfCommandOption('openpne-version', 'v', sfCommandOption::PARAMETER_OPTIONAL, 'To version', OPENPNE_VERSION),
      new sfCommandOption('revision', 'r', sfCommandOption::PARAMETER_OPTIONAL, 'To revision'),
      new sfCommandOption('no-build-model', null, sfCommandOption::PARAMETER_NONE, 'Do not build model classes'),
    ));

    $this->briefDescription = 'update OpenPNE';
    $this->detailedDescription = <<<EOF
The [openpne:migrade|INFO] task upgrades or downgrades OpenPNE and/or plugin.
Call it with:

  [./symfony openpne:migrade|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (!$options['no-build-model'])
    {
      $this->buildModel();
    }

    $databaseManager = new sfDatabaseManager($this->configuration);
    $migration = new opMigration($this->dispatcher, $databaseManager);
    $migration->migrate();
  }

  protected function buildModel()
  {
    $task = new sfPropelBuildModelTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfPropelBuildFormsTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfPropelBuildFiltersTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $task->run();
  }
}
