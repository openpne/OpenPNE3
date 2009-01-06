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
 * Subclass for performing query and update operations on the 'member_profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberProfilePeer extends BaseMemberProfileNestedSetPeer
{
  public static function getProfileListByMemberId($memberId)
  {
    $profiles = array();

    $c = new Criteria();

    parent::addSelectColumns($c);

    $c->addSelectColumn(ProfilePeer::NAME);
    $c->addSelectColumn(ProfileI18nPeer::CAPTION);

    $c->add(self::MEMBER_ID, $memberId);
    $c->add(self::LFT_KEY, 1);
    $c->addJoin(ProfilePeer::ID, ProfileI18nPeer::ID);
    $c->addJoin(ProfilePeer::ID, MemberProfilePeer::PROFILE_ID);
    $c->addAscendingOrderByColumn(ProfilePeer::SORT_ORDER);

    $stmt = self::doSelectStmt($c);
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
      $obj = new MemberProfile();
      $obj->hydrateProfiles($row);
      $profiles[] = $obj;
    }

    return $profiles;
  }

  public static function retrieveByMemberIdAndProfileId($memberId, $profileId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID, $memberId);
    $c->add(self::PROFILE_ID, $profileId);

    $result = self::doSelectOne($c);
    return $result;
  }

  public static function retrieveByMemberIdAndProfileName($memberId, $profileName)
  {
    $c = new Criteria();
    $c->add(ProfilePeer::NAME, $profileName);
    $c->add(MemberProfilePeer::MEMBER_ID, $memberId);
    $c->addJoin(MemberProfilePeer::PROFILE_ID, ProfilePeer::ID);
    return MemberProfilePeer::doSelectOne($c);
  }
}
