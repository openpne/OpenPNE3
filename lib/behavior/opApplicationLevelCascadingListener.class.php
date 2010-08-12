<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opApplicationLevelCascadingListener extends Doctrine_Record_Listener
{
  public function preDelete(Doctrine_Event $event)
  {
    $table = $event->getInvoker()->getTable();

    $targets = $this->detectCascadingTargets($table);

    foreach ($targets as $target)
    {
      $relations = $event->getInvoker()->$target['alias'];
      if (!($relations instanceof Doctrine_Collection))
      {
        if (!count($relations))
        {
          continue;
        }
        $relations = array($relations);
      }

      foreach ($relations as $record)
      {
        switch (strtolower($target['delete']))
        {
          case 'cascade':
            $record->delete();
            break;

          case 'set null':
            $record->$target['localFrom'] = null;
            $record->save();
            break;

          default:
            // do nothing
        }
      }
    }
  }

  public function preDqlDelete(Doctrine_Event $event)
  {
    $params = $event->getParams();
    $tmpQuery = clone $event->getQuery();
    $subQuery = $tmpQuery->select($params['alias'].'.'.$params['component']['table']->getIdentifier())->getDql();

    if (!in_array($params['alias'], $tmpQuery->getDqlPart('from')))
    {
      return false;
    }

    $targets = $this->detectCascadingTargets($params['component']['table']);
    foreach ($targets as $target)
    {
      $table = Doctrine::getTable($target['table']);
      $relation = $table->getRelation($target['aliasFrom']);
      $localField = $relation->getLocalFieldName();

      $q = $table->createQuery();
      $q = $q->where($q->getRootAlias().'.'.$localField.' IN ('.$subQuery.')');
      switch (strtolower($target['delete']))
      {
        case 'cascade':
          $q->delete()->execute();
          break;

        case 'set null':
          $q->update()->set($localField, '?', array(null))->execute();
          break;

        default:
          // do nothing
      }
    }
  }

  protected function detectCascadingTargets($table)
  {
    $results = array();

    // tracing relations with specified table
    foreach ($table->getRelations() as $rel)
    {
      // excluding relation that is started from the table
      if (!($rel instanceof Doctrine_Relation_ForeignKey))
      {
        continue;
      }

      // tracing relations with related table
      foreach ($rel->getTable()->getRelations() as $relRel)
      {
        // found the specified table
        if (get_class($relRel->getTable()) === get_class($table))
        {
          $results[] = array(
            'alias'     => $rel->getAlias(),
            'aliasFrom' => $relRel->getAlias(),
            'localFrom' => $relRel->getLocal(),
            'delete'    => $relRel['onDelete'],
            'table'     => $rel->getTable()->getComponentName(),
          );

          break;
        }
      }
    }

    return $results;
  }
}
