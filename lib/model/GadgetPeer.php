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
  const HOME_TYPES = 'home';

  const MOBILE_HOME_TYPES = 'mobileHome';

  const SIDE_BANNER_TYPES = 'sideBanner';

  const TOP_TYPE = 'top';

  const SIDE_MENU_TYPE = 'sideMenu';

  const CONTENTS_TYPE = 'contents';

  const BOTTOM_TYPE = 'bottom';

  const MOBILE_TOP_TYPE = 'mobileTop';

  const MOBILE_CONTENTS_TYPE = 'mobileContents';

  const MOBILE_BOTTOM_TYPE = 'mobileBottom';

  const SIDE_BANNER_CONTENTS_TYPE = 'sideBannerContents';

  static protected $homeTypes = array(self::TOP_TYPE, self::SIDE_MENU_TYPE, self::CONTENTS_TYPE, self::BOTTOM_TYPE);

  static protected $mobileHomeTypes = array(self::MOBILE_TOP_TYPE, self::MOBILE_CONTENTS_TYPE, self::MOBILE_BOTTOM_TYPE);
  
  static protected $sideBannerTypes = array(self::SIDE_BANNER_CONTENTS_TYPE);

  static protected $results;

  static public function retrieveGadgetsByTypesName($typesName)
  {
    $results = array();
    switch ($typesName)
    {
      case self::MOBILE_HOME_TYPES:
        $types = self::$mobileHomeTypes;
        break;
      case self::SIDE_BANNER_TYPES:
        $types = self::$sideBannerTypes;
        break;
      default:
        $types = self::$homeTypes;
    }
    
    foreach($types as $type)
    {
      $results[$type] = self::retrieveByType($type);
    }

    return $results;
  }

  static public function retrieveTopGadgets()
  {
    return self::retrieveByType(self::TOP_TYPE);
  }

  static public function retrieveSideMenuGadgets()
  {
    return self::retrieveByType(self::SIDE_MENU_TYPE);
  }

  static public function retrieveContentsGadgets()
  {
    return self::retrieveByType(self::CONTENTS_TYPE);
  }

  static public function retrieveBottomGadgets()
  {
    return self::retrieveByType(self::BOTTOM_TYPE);
  }

  static public function retrieveMobileTopGadgets()
  {
    return self::retrieveByType(self::MOBILE_TOP_TYPE);
  }

  static public function retrieveMobileContentsGadgets()
  {
    return self::retrieveByType(self::MOBILE_CONTENTS_TYPE);
  }

  static public function retrieveMobileBottomGadgets()
  {
    return self::retrieveByType(self::MOBILE_BOTTOM_TYPE);
  }

  static public function retrieveSideBannerContentsGadgets()
  {
    return self::retrieveByType(self::SIDE_BANNER_CONTENTS_TYPE);
  }

  static public function getTopGadgetsIds()
  {
    return self::getGadgetsIds(self::TOP_TYPE);
  }

  static public function getSideMenuGadgetsIds()
  {
    return self::getGadgetsIds(self::SIDE_MENU_TYPE);
  }

  static public function getContentsGadgetsIds()
  {
    return self::getGadgetsIds(self::CONTENTS_TYPE);
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
    if (in_array($type, self::$homeTypes))
    {
      return sfConfig::get('op_gadget_list', array());
    }
    elseif (in_array($type, self::$mobileHomeTypes))
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
