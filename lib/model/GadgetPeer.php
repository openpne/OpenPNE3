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
  const TOP_TYPE = 'top';
  const SIDE_MENU_TYPE = 'sideMenu';
  const CONTENTS_TYPE = 'contents';
  const BOTTOM_TYPE = 'bottom';

  const LOGIN_TOP_TYPE = 'loginTop';
  const LOGIN_SIDE_MENU_TYPE = 'loginSideMenu';
  const LOGIN_CONTENTS_TYPE = 'loginContents';
  const LOGIN_BOTTOM_TYPE = 'loginBottom';

  const MOBILE_TOP_TYPE = 'mobileTop';
  const MOBILE_CONTENTS_TYPE = 'mobileContents';
  const MOBILE_BOTTOM_TYPE = 'mobileBottom';

  const SIDE_BANNER_CONTENTS_TYPE = 'sideBannerContents';

  static protected $results;

  static protected function getTypes($typesName)
  {
    $types = array();
    $configs = sfConfig::get('op_gadget_config', array());
    $layoutConfigs = sfConfig::get('op_gadget_layout_config', array());
    if (!isset($configs[$typesName]))
    {
      throw new PropelException('Invalid types name');
    }
    if (isset($configs[$typesName]['layout']['choices']))
    {
      foreach ($configs[$typesName]['layout']['choices'] as $choice)
      {
        $types = array_merge($types, $layoutConfigs[$choice]);
      }
    }
    $types = array_merge($types, $layoutConfigs[$configs[$typesName]['layout']['default']]);
    $types = array_unique($types);

    if ($typesName != 'gadget')
    {
      foreach ($types as &$type)
      {
        $type = $typesName.ucfirst($type);
      }
    }

    return $types;
  }
  
  static public function retrieveGadgetsByTypesName($typesName)
  {
    $results = array();
    $types = self::getTypes($typesName);
    foreach($types as $type)
    {
      $results[$type] = self::retrieveByType($type);
    }

    return $results;
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
    $configs = sfConfig::get('op_gadget_config');
    foreach ($configs as $key => $config)
    {
      if (in_array($type, self::getTypes($key)))
      {
        $configName = 'op_'.sfInflector::underscore($key);
        if ($key != 'gadget')
        {
          $configName .= '_gadget';
        }
        $configName .= '_list';
        return sfConfig::get($configName, array());
      }
    }
    return array(); 
  }
}
