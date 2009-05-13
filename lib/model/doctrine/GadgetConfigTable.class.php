<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class GadgetConfigTable extends Doctrine_Table
{
  protected $results;

  public function retrieveByGadgetIdAndName($gadgetId, $name)
  {
    $results = $this->getResults();

    return (isset($results[$gadgetId][$name])) ? $results[$gadgetId][$name] : null;
  }

  protected function getResults()
  {
    if (is_null($this->results))
    {
      $this->results = array();
      $objects = $this->createQuery()->execute();
      foreach ($objects as $object)
      {
        $this->results[$object->getGadgetId()][$object->getName()] = $object;
      }
    }

    return $this->results;
  }
}
