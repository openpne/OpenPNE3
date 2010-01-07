<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneMigrateTask extends sfDoctrineBaseTask
{
  protected $migrationException = null;

  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'migrate';

    require sfConfig::get('sf_data_dir').'/version.php';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('target', null, sfCommandOption::PARAMETER_OPTIONAL, 'The target of migration. This must be "OpenPNE" or a plugin name.'),
      new sfCommandOption('no-update-plugin', null, sfCommandOption::PARAMETER_NONE, 'Do not update plugins'),
      new sfCommandOption('no-build-model', null, sfCommandOption::PARAMETER_NONE, 'Do not build model classes'),
    ));

    $this->briefDescription = 'migrate OpenPNE and/or the plugins to newer/older version one';
    $this->detailedDescription = <<<EOF
The [openpne:migrate|INFO] task lets OpenPNE migrate and/or the plugins newer version.

Call it with:
  1.  [./symfony openpne:migrate|INFO]
  2.  [./symfony openpne:migrate --target=opSamplePlugin|INFO]
  3.  [./symfony openpne:migrate --target=OpenPNE|INFO]

    1. In the first form, any targets aren't specified.
       This task executes the migration scripts for OpenPNE and all the plugins to newer revision.

    2. In the second form, the specified plugin will be migrated newer revision.

    3. In the third form, OpenPNE will be migrated newer revision.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    new sfDatabaseManager($this->configuration);  // opening connection

    @$this->createCacheDirectory();

    $oldPluginList = sfFinder::type('dir')->in(sfConfig::get('sf_plugins_dir'));
    if (!$options['no-update-plugin'])
    {
      $this->installPlugins($options['target']);
    }
    $newPluginList = sfFinder::type('dir')->name('op*Plugin')->maxdepth(1)->in(sfConfig::get('sf_plugins_dir'));
    foreach ($oldPluginList as $k => $v)
    {
      $pluginName = basename($v);
      if ((bool)Doctrine::getTable('SnsConfig')->get($pluginName.'_needs_data_load', false))
      {
        // It needs initializing
        unset($oldPluginList[$k]);
      }
    }
    $installedPlugins = array_map('basename', array_diff($newPluginList, $oldPluginList));

    if (!$options['no-build-model'])
    {
      $this->buildModel($options);
    }

    foreach ($installedPlugins as $v)
    {
      $modelDir = sfConfig::get('sf_lib_dir').'/model/doctrine/'.$v;
      if (!is_dir($modelDir))
      {
        continue;
      }

      Doctrine::createTablesFromModels($modelDir);
    }

    if ($options['target'])
    {
      $targets = array($options['target']);
    }
    else
    {
      $targets = array_merge(array('OpenPNE'), $this->getEnabledOpenPNEPlugin());
    }
    $databaseManager = new sfDatabaseManager($this->configuration);
    foreach ($targets as $target)
    {
      if (in_array($target, $installedPlugins))
      {
        continue;
      }

      try
      {
        $this->migrateFromScript($target, $databaseManager);
      }
      catch (Doctrine_Migration_Exception $e)
      {
        if ('OpenPNE' === $target)
        {
          throw $e;
        }

        $errorText = '';

        $errors = array();
        preg_match_all('/Error #[0-9]+ \- .*$/m', $e->getMessage(), $errors, PREG_SET_ORDER);
        foreach ($errors as $error)
        {
          $errorText .= $error[0]."\n";
        }

        $e = new Exception(sprintf("migrating of %s encountered the following errors:\n %s", $target, $errorText));
        $this->commandApplication->renderException($e);
      }
    }

    $targets = array_merge($targets, $installedPlugins);
    foreach ($targets as $target)
    {
      $this->dataLoadForInitializePlugin($target);
    }

    if ($this->migrationException)
    {
      throw $this->migrationException;
    }

    $task = new sfPluginPublishAssetsTask($this->dispatcher, $this->formatter);
    $task->run(array(), array());
  }

  protected function migrateFromScript($target, $databaseManager)
  {
    try
    {
      $migration = new opMigration($this->dispatcher, $databaseManager, $target, null);
      if (!$migration->hasMigrationScriptDirectory())
      {
        $this->logSection('migrate', sprintf('%s is not supporting migration.', $target));
        return false;
      }

      $migration->migrate();
    }
    catch (Doctrine_Migration_Exception $e)
    {
      $this->throwSpecifiedException($e);
    }
    $this->logSection('migrate', sprintf('%s is now at revision %d.', $target, $migration->getCurrentVersion()));
  }

  protected function dataLoadForInitializePlugin($pluginName)
  {
    if ('OpenPNE' === $pluginName)
    {
      return null;
    }

    $fixturesDir = sfConfig::get('sf_plugins_dir').'/'.$pluginName.'/data/fixtures';
    if ((bool)Doctrine::getTable('SnsConfig')->get($pluginName.'_needs_data_load', false)
      && is_readable($fixturesDir))
    {
      $this->logSection('doctrine', sprintf('loading data fixtures for "%s"', $pluginName));

      $config = $this->getCliConfig();

      Doctrine::loadModels($config['models_path']);
      Doctrine::loadData(array($fixturesDir), true);
    }

    Doctrine::getTable('SnsConfig')->set($pluginName.'_needs_data_load', '0');
  }

  protected function throwSpecifiedException(Exception $e)
  {
    if (0 !== strpos($e->getMessage(), '1 error(s) encountered during migration'))
    {
      throw $e;
    }

    if (false === strpos($e->getMessage(), 'Already at version #'))
    {
      throw $e;
    }
  }

  protected function buildModel($options)
  {
    $task = new sfDoctrineBuildTask($this->dispatcher, $this->formatter);
    $task->setCommandApplication($this->commandApplication);
    $task->setConfiguration($this->configuration);
    $task->run(array(), array(
      'no-confirmation' => true,
      'model'           => true,
      'forms'           => true,
      'filters'         => true,
      'application'     => $options['application'],
      'env'             => $options['env'],
    ));

    $task = new sfCacheClearTask($this->dispatcher, $this->formatter);
    @$task->run();
    $task = new openpnePermissionTask($this->dispatcher, $this->formatter);
    @$task->run();
  }

  protected function installPlugins($target = null)
  {
    if ('OpenPNE' === $target)
    {
      return null;
    }

    $task = new sfCacheClearTask($this->dispatcher, $this->formatter);
    @$task->run();
    $task = new openpnePermissionTask($this->dispatcher, $this->formatter);
    @$task->run();

    $options = array();
    if ($target)
    {
      $options[] = '--target='.$target;
    }

    $task = new opPluginSyncTask($this->dispatcher, $this->formatter);
    $task->run(array(), $options);
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

  protected function createCacheDirectory()
  {
    $this->getFilesystem()->mkdirs(sfConfig::get('sf_cache_dir'), 0777);
  }
}
