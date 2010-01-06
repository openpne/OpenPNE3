<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class openpneDataMaskTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'data-mask';

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', true),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('file', 'f', sfCommandOption::PARAMETER_OPTIONAL, 'The path to masking rule definition file.', sfConfig::get('sf_config_dir').'/mask.yml'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
    ));

    $this->briefDescription = 'mask data';
    $this->detailedDescription = <<<EOF
The [openpne:data-mask|INFO] task masks data.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $yaml = sfYaml::load($options['file']);

    Doctrine::initializeModels(array_keys($yaml));

    foreach ($yaml as $model => $tasks)
    {
      $table = Doctrine::getTable($model);
      $table->getRecordListener()->setOption('disabled', true);

      foreach ($tasks as $key => $task)
      {
        $this->logSection('mask', sprintf('Masking data of the "%s" model ("%s" task)', $model, $key));

        $q = $table->createQuery();

        $q = $this->parseCondition($q, $model, (array)$task['condition']);
        $q = $this->parseValue($q, $model, (array)$task['values'], (array)$task['options']);

        if ($q)
        {
          $q->execute();
        }
        $this->logSection('mask', sprintf('Masked data of the "%s" model ("%s" task)', $model, $key));
      }
    }
  }

  protected function parseCondition($q, $modelName, $condition)
  {
    $table = Doctrine::getTable($modelName);
    $table->getRecordListener()->setOption('disabled', true);

    foreach ($condition as $key => $value)
    {
      if ($table->hasRelation($key))
      {
        $q->leftJoin($key);
        $q = $this->parseCondition($q, $key, $value);
      }
      else
      {
        $q->andWhere($modelName.'.'.$key.' = ?', $value);
      }
    }

    return $q;
  }

  protected function parseValue($q, $modelName, $values, $options = array())
  {
    $q->update();

    foreach ($values as $column => $value)
    {
      if (!empty($options['filter']))
      {
        $value = $options['filter']($value);
      }

      if (!empty($options['unique']))
      {
        $uniqueIdColumn = 'id';
        if (!empty($options['unique_id_column']))
        {
          $uniqueIdColumn = $options['unique_id_column'];
        }

        $q->select();
        $result = $q->execute();
        foreach ($result as $record)
        {
          $record->set($column, $record->$uniqueIdColumn.$value);
          $record->save();
        }

        $q = null;
      }
      else
      {
        $q->set($modelName.'.'.$column, '?', $value);
      }
    }

    return $q;
  }
}
