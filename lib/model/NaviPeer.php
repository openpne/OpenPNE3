<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Subclass for performing query and update operations on the 'navi' table.
 *
 * 
 *
 * @package lib.model
 */ 
class NaviPeer extends BaseNaviPeer
{
  public static function retrieveByType($type)
  {
    $c = new Criteria();
    $c->add(self::TYPE, $type);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);
    return self::doSelect($c);
  }

  public static function retrieveTypes()
  {
    $result = array();

    $defaultTypes = array(
      'insecure_global',
      'secure_global',
      'default',
      'friend',
      'community',
    );

    $c = new Criteria();
    $c->clearSelectColumns();
    $c->addSelectColumn(self::TYPE);
    $c->addGroupByColumn(self::TYPE);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);
    $stmt = self::doSelectStmt($c);
    while ($res = $stmt->fetchColumn(1)) {
      $result[] = $res;
    }

    return array_unique(array_merge($defaultTypes, $result));
  }
}
