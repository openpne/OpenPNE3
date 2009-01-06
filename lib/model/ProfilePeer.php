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

/**
 * Subclass for performing query and update operations on the 'profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class ProfilePeer extends BaseProfilePeer
{
  public static function retrieveByIsDispRegist()
  {
    $c = new Criteria();
    $c->add(self::IS_DISP_REGIST, true);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function retrieveByIsDispConfig()
  {
    $c = new Criteria();
    $c->add(self::IS_DISP_CONFIG, true);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function retrieveByIsDispSearch()
  {
    $c = new Criteria();
    $c->add(self::IS_DISP_SEARCH, true);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    $result = self::doSelectOne($c);
    return $result;
  }

  public static function getMaxSortOrder()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelectOne($c);
    if ($result)
    {
      return $result->getSortOrder();
    }
    return 0;
  }
}
