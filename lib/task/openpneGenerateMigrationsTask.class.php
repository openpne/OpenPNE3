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

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

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
    $ymlTmp = $tmpdir.'/yaml';
    $modelTmp = $tmpdir.'/model';
    $this->getFileSystem()->mkdirs($ymlTmp);
    $this->getFileSystem()->mkdirs($modelTmp);
    $migrationsPath = sfConfig::get('sf_data_dir').'/migrations/generated';

    $config = $this->getCliConfig();

    $manager = Doctrine_Manager::getInstance();
    $oldAttr = $manager->getAttribute(Doctrine::ATTR_MODEL_LOADING);
    $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_AGGRESSIVE);

    sfSimpleAutoload::unregister();
    Doctrine::generateYamlFromDb($ymlTmp.'/from.yml', array(), array('generateBaseClasses' => false));
    sfSimpleAutoload::register();
    $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, $oldAttr);

    $models = sfFinder::type('file')->name('*.php')->in($config['models_path']);
    foreach ($models as $model)
    {
      $dirname = basename(dirname($model));
      $filename = basename($model);
      if ('base' !== $dirname)
      {
        continue;
      }

      $normalModelName = str_replace('Base', '', basename($model, '.class.php'));
      $normalModelRefClass = new ReflectionClass($normalModelName);
      if ($normalModelRefClass && $normalModelRefClass->isAbstract())
      {
        continue;
      }

      $content = file_get_contents($model);
      $content = str_replace('abstract class Base', 'class ToPrfx', $content);
      $content = str_replace('extends opDoctrineRecord', 'extends Doctrine_Record', $content);

      $matches = array();
      if (preg_match('/\$this->setTableName\(\'([^\']+)\'\);/', $content, $matches))
      {
        $tableName = $matches[1];
        $content = preg_replace('/class [a-zA-Z0-9_]+/', 'class ToPrfx'.Doctrine_Inflector::classify($tableName), $content);
        file_put_contents($modelTmp.'/ToPrfx'.Doctrine_Inflector::classify($tableName).'.class.php', $content);
      }
      else
      {
        file_put_contents($modelTmp.'/'.str_replace('Base', 'ToPrfx', $filename), $content);
      }
    }

    $migration = new Doctrine_Migration($migrationsPath);
    $diff = new opMigrationDiff($ymlTmp.'/from.yml', $modelTmp, $migration);
    $changes = $diff->generateMigrationClasses();

    $this->getFileSystem()->remove($ymlTmp);
    $this->getFileSystem()->remove($modelTmp);

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
