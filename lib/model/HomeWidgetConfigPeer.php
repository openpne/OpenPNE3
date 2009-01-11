<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class HomeWidgetConfigPeer extends BaseHomeWidgetConfigPeer
{
  static protected $results;

  static public function retrieveByWidgetIdAndName($widgetId, $name)
  {
    $results = self::getResults();

    return (isset($results[$widgetId][$name])) ? $results[$widgetId][$name] : null;
  }

  static protected function getResults()
  {
    if (is_null(self::$results))
    {
      self::$results = array();
      foreach (self::doSelect(new Criteria()) as $object)
      {
        self::$results[$object->getHomeWidgetId()][$object->getName()] = $object;
      }
    }

    return self::$results;
  }
}
