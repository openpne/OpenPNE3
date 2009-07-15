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
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'migrate';

    require sfConfig::get('sf_data_dir').'/version.php';

    $this->addOptions(array(
      new sfCommandOption('target', null, sfCommandOption::PARAMETER_OPTIONAL, 'The target of migration. This must be "OpenPNE" or a plugin name.'),
      new sfCommandOption('to-version', 'v', sfCommandOption::PARAMETER_OPTIONAL, 'A version'),
      new sfCommandOption('to-revision', 'r', sfCommandOption::PARAMETER_OPTIONAL, 'A revision'),
      new sfCommandOption('no-update-plugin', null, sfCommandOption::PARAMETER_NONE, 'Do not update plugins'),
      new sfCommandOption('no-build-model', null, sfCommandOption::PARAMETER_NONE, 'Do not build model classes'),
    ));

    $this->briefDescription = 'migrate OpenPNE and/or the plugins to newer/older version one';
    $this->detailedDescription = <<<EOF
The [openpne:migrade|INFO] task lets OpenPNE migrate and/or the plugins newer/older version.

Call it with:
  1.  [./symfony openpne:migrade|INFO]
  2.  [./symfony openpne:migrade --target=opSamplePlugin|INFO]
  3.  [./symfony openpne:migrade -r 10 --target=OpenPNE|INFO]
  4.  [./symfony openpne:migrade -v 3.0.2 --target=OpenPNE|INFO]

    1. In the first form, any targets, versions and revisions aren't specified.
       This task executes the migration scripts for OpenPNE and all the plugins to newer version.

    2. In the second form, the specified target (OpenPNE or a plugin) will be migrated newer version.

    3. In the third form, the specified target (OpenPNE or a plugin) will be migrated specified revision (internal version).

    4. In the fourth form, the specified target (OpenPNE or a plugin) will be migrated specified version.

  When the specified value of the [-r|INFO] option or the [-v|INFO] option is newer than the current revision of the target, it will be upgraded.
  And, when the specified value of the [-r|INFO] option or the [-v|INFO] option is older than the current revision of the target, it will be downgraded.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (!$options['no-update-plugin'])
    {
      $this->installPlugins();
    }

    if (!$options['no-build-model'])
    {
      $this->buildModel();
    }

    $targets = array_merge(array('OpenPNE'), $this->getEnabledOpenPNEPlugin());
    $databaseManager = new sfDatabaseManager($this->configuration);
    foreach ($targets as $target)
    {
      $params = array(
        'version'  => $options['to-version'],
        'revision' => $options['to-revision'],
      );
      $this->migrateFromScript($target, $databaseManager, $params);
    }

    $this->migrateFromDiff();
  }

  protected function migrateFromScript($target, $databaseManager, $params)
  {
    try
    {
      $migration = new opMigration($this->dispatcher, $databaseManager, $target, null, $params);
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

  protected function migrateFromDiff()
  {
    $tmpdir = sfConfig::get('sf_cache_dir').'/models_tmp';
    $this->getFilesystem()->mkdirs($tmpdir);
    $this->getFilesystem()->remove(sfFinder::type('file')->in(array($tmpdir)));

    @exec('./symfony openpne:generate-migrations');

    $migrationsPath = sfConfig::get('sf_data_dir').'/migrations/generated';
    $this->callDoctrineCli('migrate', array('migrations_path' => $migrationsPath));
  }

  protected function buildModel()
  {
    $task = new sfDoctrineBuildModelTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfDoctrineBuildFormsTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfDoctrineBuildFiltersTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new openpnePermissionTask($this->dispatcher, $this->formatter);
    $task->run();
  }

  protected function installPlugins()
  {
    $task = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new openpnePermissionTask($this->dispatcher, $this->formatter);
    $task->run();
    $task = new opPluginSyncTask($this->dispatcher, $this->formatter);
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
