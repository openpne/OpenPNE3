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

class HomeWidgetPeer extends BaseHomeWidgetPeer
{
  static public function retrieveTopWidgets()
  {
    return self::retrieveByType('top');
  }

  static public function retrieveSideMenuWidgets()
  {
    return self::retrieveByType('sideMenu');
  }

  static public function retrieveContentsWidgets()
  {
    return self::retrieveByType('contents');
  }

  static public function getTopWidgetsIds()
  {
    return self::getWidgetsIds('top');
  }

  static public function getSideMenuWidgetsIds()
  {
    return self::getWidgetsIds('sideMenu');
  }

  static public function getContentsWidgetsIds()
  {
    return self::getWidgetsIds('contents');
  }

  static public function retrieveByType($type)
  {
    $c = new Criteria();
    $c->add(self::TYPE, $type);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);
    return self::doSelect($c);
  }

  static public function getWidgetsIds($type)
  {
    $result = array();

    $c = new Criteria();
    $c->clearSelectColumns()->addSelectColumn(self::ID);
    $c->add(self::TYPE, $type);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);
    $stmt = self::doSelectStmt($c);

    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[] = $row[0];
    }

    return $result;
  }
}
