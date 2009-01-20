<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class GadgetConfigPeer extends BaseGadgetConfigPeer
{
  static protected $results;

  static public function retrieveByGadgetIdAndName($gadgetId, $name)
  {
    $results = self::getResults();

    return (isset($results[$gadgetId][$name])) ? $results[$gadgetId][$name] : null;
  }

  static protected function getResults()
  {
    if (is_null(self::$results))
    {
      self::$results = array();
      foreach (self::doSelect(new Criteria()) as $object)
      {
        self::$results[$object->getGadgetId()][$object->getName()] = $object;
      }
    }

    return self::$results;
  }
}
