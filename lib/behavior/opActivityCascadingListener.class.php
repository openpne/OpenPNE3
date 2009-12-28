<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opActivityCascadingListener extends Doctrine_Record_Listener
{
  public function preDelete(Doctrine_Event $event)
  {
    $id = $event->getInvoker()->getId();
    $foreignTableName = $event->getInvoker()->getTable()->getTableName();
    $records = Doctrine::getTable('ActivityData')->findByForeignTableAndForeignId($foreignTableName, $id);
    if ($records instanceof Doctrine_Collection)
    {
      $records->delete();
    }
  }

  public function preDqlDelete(Doctrine_Event $event)
  {
    $params = $event->getParams();
    $query = $event->getQuery();
    $table = $params['component']['table'];
    $identifier = $table->getIdentifier();
    $tmpQuery = clone $event->getQuery();
    $subQuery = $tmpQuery->select($params['alias'].'.'.$identifier);
    $records = $subQuery->execute();
    if ($records instanceof Doctrine_Collection && $records->count())
    {
      $q = Doctrine::getTable('ActivityData')->createQuery()
        ->where('foreign_table = ?', $table->getTableName())
        ->andWhereIn('foreign_id', array_values($records->toKeyValueArray($identifier, $identifier)))
        ->delete()
        ->execute();
    }
  }
}
