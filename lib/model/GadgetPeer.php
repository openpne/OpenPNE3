<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class GadgetPeer extends BaseGadgetPeer
{
  static protected $pcTypes = array('top', 'sideMenu', 'contents', 'bottom');

  static protected $mobileTypes = array('mobileTop', 'mobileContents', 'mobileBottom');
  
  static protected $sideBannerTypes = array('sideBannerContents');

  static protected $results;

  static public function retrieveTopGadgets()
  {
    return self::retrieveByType('top');
  }

  static public function retrieveSideMenuGadgets()
  {
    return self::retrieveByType('sideMenu');
  }

  static public function retrieveContentsGadgets()
  {
    return self::retrieveByType('contents');
  }

  static public function retrieveBottomGadgets()
  {
    return self::retrieveByType('bottom');
  }

  static public function retrieveMobileTopGadgets()
  {
    return self::retrieveByType('mobileTop');
  }

  static public function retrieveMobileContentsGadgets()
  {
    return self::retrieveByType('mobileContents');
  }

  static public function retrieveMobileBottomGadgets()
  {
    return self::retrieveByType('mobileBottom');
  }

  static public function retrieveSideBannerContentsGadgets()
  {
    return self::retrieveByType('sideBannerContents');
  }

  static public function getTopGadgetsIds()
  {
    return self::getGadgetsIds('top');
  }

  static public function getSideMenuGadgetsIds()
  {
    return self::getGadgetsIds('sideMenu');
  }

  static public function getContentsGadgetsIds()
  {
    return self::getGadgetsIds('contents');
  }

  static public function retrieveByType($type)
  {
    $results = self::getResults();

    return (isset($results[$type])) ? $results[$type] : null;
  }

  static public function getGadgetsIds($type)
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

  static public function getGadgetConfigListByType($type)
  {
    if (in_array($type, self::$pcTypes))
    {
      return sfConfig::get('op_gadget_list', array());
    }
    elseif (in_array($type, self::$mobileTypes))
    {
      return sfConfig::get('op_mobile_gadget_list', array());
    }
    elseif (in_array($type, self::$sideBannerTypes))
    {
      return sfConfig::get('op_side_banner_gadget_list', array());
    }
    return array();
  }
}
