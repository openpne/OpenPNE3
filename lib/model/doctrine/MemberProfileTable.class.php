<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberProfileTable extends Doctrine_Table
{
  public function getProfileListByMemberId($memberId)
  {
    $memberProfiles = $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('lft = 1')
      ->orderBy('p.sort_order')
      ->leftJoin('MemberProfile.Profile p')
      ->execute();

    return $memberProfiles;

    $stmt = self::doSelectStmt($c);
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $obj = new MemberProfile();
      $obj->hydrateProfiles($row);
      $profiles[] = $obj;
    }

    return $profiles;
  }

  public function getViewableProfileListByMemberId($memberId, $myMemberId = null)
  {
    if (is_null($myMemberId))
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $profiles = $this->getProfileListByMemberId($memberId);
    foreach ($profiles as $key => $profile)
    {
      if (!$profile->isViewable($myMemberId))
      {
        unset($profiles[$key]);
      }
    }

    return $profiles;
  }

  public function retrieveByMemberIdAndProfileId($memberId, $profileId)
  {
    return $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('profile_id = ?', $profileId)
      ->fetchOne();
  }

  public function retrieveByMemberIdAndProfileName($memberId, $profileName)
  {
    return $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('p.name = ?', $profileName)
      ->leftJoin('MemberProfile.Profile p')
      ->fetchOne();
  }

  public function searchMemberIds($profile = array(), $ids = null)
  {
    foreach ($profile as $key => $value)
    {
      $item = Doctrine::getTable('Profile')->retrieveByName($key);
      $_result = array();
      $column = 'value';
      if ($item->getFormType() === 'date')
      {
        $options = $item->getProfileOption();
        $i = 0;
        foreach ($value as $k => $v)
        {
          $option = $options[$i++];
          if ($v)
          {
            $ids = $this->filterMemberIdByProfileOption($ids, $column, $v, $option, array());
          }
        }
        continue;
      }
      elseif (is_array($value))
      {
        $column = 'profile_option_id';
      }

      $ids = $this->filterMemberIdByProfile($ids, $column, $value, $item, array());
    }

    return $ids;
  }

  public function filterMemberIdByProfile($ids, $column, $value, Profile $item, $choices)
  {
    $_result = array();
    $q = Doctrine::getTable('MemberProfile')->createQuery();
    $list = opFormItemGenerator::filterSearchQuery($q, $column, $value, $item->toArray(), array())
      ->select('member_id')
      ->andWhere('profile_id = ?', $item->getId())
      ->execute();

    foreach ($list as $value)
    {
      $_result[] = $value->getMemberId();
    }

    if (is_array($ids))
    {
      $ids = array_values(array_intersect($ids, $_result));
    }
    else
    {
      $ids = array_values($_result);
    }

    return $ids;
  }

  public function filterMemberIdByProfileOption($ids, $column, $value, ProfileOption $item, $choices)
  {
    $_result = array();

    $list = Doctrine::getTable('MemberProfile')->createQuery()
      ->select('member_id')
      ->where($column.'= ?', $value)
      ->andWhere('profile_option_id = ?', $item->getId())
      ->execute();

    foreach ($list as $value)
    {
      $_result[] = $value->getMemberId();
    }

    if (is_array($ids))
    {
      $ids = array_values(array_intersect($ids, $_result));
    }
    else
    {
      $ids = array_values($_result);
    }

    return $ids;
  }

  public function createChild(Doctrine_Record $parent, $memberId, $profileId, $optionIds, $values = array())
  {
    $parent->clearChildren();

    foreach ($optionIds as $i => $optionId)
    {
      $childProfile = new MemberProfile();
      $childProfile->setMemberId($memberId);
      $childProfile->setProfileId($profileId);
      $childProfile->setProfileOptionId($optionId);
      if (isset($values[$i]))
      {
        $childProfile->setValue($values[$i]);
      }
      $childProfile->getNode()->insertAsLastChildOf($parent);
      $childProfile->save();
    }
  }
}
