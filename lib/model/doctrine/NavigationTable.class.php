<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class NavigationTable extends Doctrine_Table
{
  public function retrieveByType($type)
  {
    return $this->createQuery()
      ->where('type = ?', $type)
      ->orderBy('sort_order')
      ->execute();
  }

  public function retrieveTypes($isMobile)
  {
    $result = array();

    if ($isMobile)
    {
      $defaultTypes = array(
        'mobile_global',
        'mobile_home',
        'mobile_home_side',
        'mobile_friend',
        'mobile_community',
      );
    }
    else
    {
      $defaultTypes = array(
        'insecure_global',
        'secure_global',
        'default',
        'friend',
        'community',
      );
    }

    $resultSet = $this->createQuery()
      ->select('type')
      ->groupBy('type')
      ->orderBy('sort_order')
      ->fetchArray();

    foreach ($resultSet as $item)
    {
      $result[] = $item['type'];
    }

    return array_unique(array_merge($defaultTypes, $result));
  }
}
