<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class HomeWidgetPeer extends BaseHomeWidgetPeer
{
  static protected $results;

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

  static public function retrieveMobileTopWidgets()
  {
    return self::retrieveByType('mobileTop');
  }

  static public function retrieveMobileContentsWidgets()
  {
    return self::retrieveByType('mobileContents');
  }

  static public function retrieveMobileBottomWidgets()
  {
    return self::retrieveByType('mobileBottom');
  }

  static public function retrieveSideBannerContentsWidgets()
  {
    return self::retrieveByType('sideBannerContents');
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
    $results = self::getResults();

    return (isset($results[$type])) ? $results[$type] : null;
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

  static protected function getResults()
  {
    if (is_null(self::$results))
    {
      $criteria = new Criteria();
      $criteria->addAscendingOrderByColumn(self::SORT_ORDER);

      self::$results = array();
      foreach (self::doSelect($criteria) as $object)
      {
        self::$results[$object->getType()][] = $object;
      }
    }

    return self::$results;
  }
}
