<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneGenerateMigrationsTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'generate-migrations';

    $this->briefDescription = 'generates migration scripts automatically';
    $this->detailedDescription = <<<EOF
The [./symfony openpne:generate-migrations|INFO] task generates migration scripts automatically.

Call it with:
  [./symfony openpne:generate-migrations|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    $tmpdir = sfConfig::get('sf_cache_dir').'/models_tmp';
    $migrationsPath = sfConfig::get('sf_data_dir').'/migrations/generated';

    $config = $this->getCliConfig();

    $this->callDoctrineCli('generate-models-db', array('models_path' => $tmpdir));

    $migration = new Doctrine_Migration($migrationsPath);
    $diff = new opMigrationDiff($tmpdir, $config['models_path'], $migration);
    $changes = $diff->generateMigrationClasses();

    $numChanges = count($changes, true) - count($changes);

    if (!$numChanges)
    {
      throw new Doctrine_Task_Exception('Could not generate migration classes from difference');
    }
    else
    {
      $this->dispatcher->notify(new sfEvent($this, 'command.log', array($this->formatter->formatSection('doctrine', 'Generated migration classes successfully from difference'))));
    }
  }
}
