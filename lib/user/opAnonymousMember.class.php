<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAnonymousMember is the dummy model class for representing pre-login member.
 *
 * @package    OpenPNE
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAnonymousMember extends Member
{
  public function getId()
  {
    return 0;
  }

  public function getName()
  {
    return 'Anonymous';
  }

  public function getIsLoginRejected()
  {
    return false;
  }

  public function getInviteMemberId()
  {
    return null;
  }

  public function getProfiles($viewableCheck = false, $myMemberId = null)
  {
    return array();
  }

  public function getProfile($profileName, $viewableCheck = false, $myMemberId = null)
  {
    return null;
  }

  public function getAge($viewableCheck = false, $myMemberId = null)
  {
    return false;
  }

  public function getConfig($configName)
  {
    return null;
  }

  public function setConfig($configName, $value, $isDateTime = false)
  {
    return null;
  }

  public function getFriends($limit = null, $isRandom = false)
  {
    return Doctrine_Collection::create('Member');
  }

  public function countFriends()
  {
    return 0;
  }

  public function getJoinCommunities($limit = null, $isRandom = false)
  {
    return Doctrine_Collection::create('Community');
  }

  public function getFriendPreTo(Doctrine_Query $q = null)
  {
    return Doctrine_Collection::create('MemberRelationship');
  }

  public function countFriendPreTo(Doctrine_Query $q = null)
  {
    return Doctrine_Collection::create('MemberRelationship');
  }

  public function getFriendPreFrom(Doctrine_Query $q = null)
  {
    return Doctrine_Collection::create('MemberRelationship');
  }

  public function countFriendPreFrom(Doctrine_Query $q = null)
  {
    return 0;
  }

  public function getImage()
  {
    return false;
  }

  public function getImageFileName()
  {
    return false;
  }

  public function updateLastLoginTime()
  {
  }

  public function getLastLoginTime()
  {
    return time();
  }

  public function isOnBlackList()
  {
    return false;
  }

  public function getInvitingMembers()
  {
    return Doctrine_Collection::create('Member');
  }

  public function getInviteMember()
  {
    return false;
  }

  public function getEmailAddress($isPriorityMobile = null)
  {
    return null;
  }

  public function getEmailAddresses()
  {
    return null;
  }

  public function generateRoleId(Member $member)
  {
    return 'everyone';
  }

  public function save()
  {
    return false;
  }

  public function delete()
  {
    return false;
  }
}
