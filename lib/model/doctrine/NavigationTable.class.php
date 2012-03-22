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

  public function getTypesByAppName($appName)
  {
    switch($appName)
    {
      case 'mobile' :
        return array(
          'mobile_global',
          'mobile_home',
          'mobile_home_center',
          'mobile_home_side',
          'mobile_friend',
          'mobile_community',
        );
      case 'smartphone' :
        return array(
          'smartphone_insecure',
          'smartphone_default',
        );
      case 'backend' :
        return array(
          'backend_side'
        );
      default :
        return array(
          'insecure_global',
          'secure_global',
          'default',
          'friend',
          'community',
        );
    }
  }
}
