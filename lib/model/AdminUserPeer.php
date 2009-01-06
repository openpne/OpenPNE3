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
 * Subclass for performing query and update operations on the 'admin_user' table.
 *
 * 
 *
 * @package lib.model
 */ 
class AdminUserPeer extends BaseAdminUserPeer
{
  public static function retrieveByUsername($username)
  {
    $c = new Criteria();
    $c->add(self::USERNAME, $username);
    return self::doSelectOne($c);
  }

  public static function retrievesAll()
  {
    $c = new Criteria();
    return self::doSelect($c);
  }
}
