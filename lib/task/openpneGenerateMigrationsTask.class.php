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
    $this->callDoctrineCli('generate-migrations-diff', array('models_path' => $tmpdir, 'migrations_path' => $migrationsPath, 'yaml_schema_path' => $config['models_path']));
  }
}
