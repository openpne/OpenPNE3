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

    $this->addOptions(array(
      new sfCommandOption('target', null, sfCommandOption::PARAMETER_OPTIONAL, 'The target of migration'),
      new sfCommandOption('to-version', 'v', sfCommandOption::PARAMETER_OPTIONAL, 'To version'),
      new sfCommandOption('to-revision', 'r', sfCommandOption::PARAMETER_OPTIONAL, 'To revision'),
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
    if (!in_array('sfDoctrinePlugin', $this->configuration->getPlugins()))
    {
      throw new sfCommandException("This task requires sfDoctrinePlugin.\nPlease enable the plugin by your config/ProjectConfiguration.class.php");
    }

    if (!$options['target'] && ($options['to-version'] || $options['to-revision']))
    {
      throw new sfCommandException("You can't specify the to-version option or the to-revision option without the target option");
    }

    if (!$options['no-build-model'])
    {
      $this->buildModel();
    }

    $databaseManager = new sfDatabaseManager($this->configuration);

    if (!empty($options['target']))
    {
      $targets = array($options['target']);
    }
    else
    {
      $targets = array_merge(array('OpenPNE'), $this->getEnabledOpenPNEPlugin());
    }

    foreach ($targets as $target)
    {
      $migration = new opMigration($this->dispatcher, $databaseManager, $target, null, $options['to-version']);

      try
      {
        $migration->migrate();
      }
      catch (Doctrine_Migration_Exception $e)
      {
        if (0 !== strpos($e->getMessage(), 'Already at version #'))
        {
          throw $e;
        }
      }

      $this->logSection('migrate', sprintf('%s is now at revision %d.', $target, $migration->getCurrentVersion()));
    }
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

  protected function getEnabledOpenPNEPlugin()
  {
    $list = $this->configuration->getPlugins();
    $result = array();

    foreach ($list as $value)
    {
      if (!strncmp($value, 'op', 2))
      {
        $result[] = $value;
      }
    }

    return $result;
  }
}
