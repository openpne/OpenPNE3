<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberProfileTable extends opAccessControlDoctrineTable
{
  public function getProfileListByMemberId($memberId)
  {
    $profiles = Doctrine::getTable('Profile')->createQuery()
      ->select('id')
      ->orderBy('sort_order')
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $queryCacheHash = '';

    $q = $this->createQuery()
      ->where('member_id = ?')
      ->andWhere('profile_id = ?');

    $memberProfiles = array();
    foreach ($profiles as $profile)
    {
      if ($queryCacheHash)
      {
        $q->setCachedQueryCacheHash($queryCacheHash);
      }

      $memberProfile = $q->fetchOne(array($memberId, $profile[0]));
      if ($memberProfile)
      {
        $memberProfiles[] = $memberProfile;
      }

      if (!$queryCacheHash)
      {
        $queryCacheHash = $q->calculateQueryCacheHash();
      }
    }

    // NOTICE: this returns Array not Doctrine::Collection
    return $memberProfiles;
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

  public function getViewableProfileByMemberIdAndProfileName($memberId, $profileName, $myMemberId = null)
  {
    if (is_null($myMemberId))
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $profile = $this->retrieveByMemberIdAndProfileName($memberId, $profileName);

    if ($profile && $profile->isViewable($myMemberId))
    {
      return $profile;
    }

    return false;
  }

  public function retrieveByMemberIdAndProfileId($memberId, $profileId)
  {
    return $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('profile_id = ?', $profileId)
      ->fetchOne();
  }

  public function retrieveByMemberIdAndProfileName($memberId, $profileName, $hydrationMode = Doctrine::HYDRATE_RECORD)
  {
    static $queryCacheHash;

    $profileId = Doctrine::getTable('Profile')->getProfileNameById($profileName);
    if ($profileId)
    {
      $q = $this->createQuery()
        ->where('member_id = ?', $memberId)
        ->andWhere('profile_id = ?', $profileId);

      if (!$queryCacheHash)
      {
        $result = $q->fetchOne(array(), $hydrationMode);

        $queryCacheHash = $q->calculateQueryCacheHash();
      }
      else
      {
        $q->setCachedQueryCacheHash($queryCacheHash);

        $result = $q->fetchOne(array(), $hydrationMode);
      }

      if (Doctrine::HYDRATE_SCALAR == $hydrationMode)
      {
        if (!$result['MemberProfile_profile_option_id'])
        {
          return $result;
        }

        $option = Doctrine::getTable('ProfileOptionTranslation')->createQuery()
          ->select('value')
          ->where('id = ?', $result['MemberProfile_profile_option_id'])
          ->andWhere('lang = ?', sfContext::getInstance()->getUser()->getCulture())
          ->fetchOne(array(), Doctrine::HYDRATE_NONE);

        if ($option)
        {
          $result['MemberProfile_value'] = $option[0];
        }
      }

      return $result;
    }

    return null;
  }

  public function searchMemberIds($profile = array(), $ids = null, $isCheckPublicFlag = true)
  {
    $publicFlag = ($isCheckPublicFlag) ? 1 : null;

    foreach ($profile as $key => $value)
    {
      $item = Doctrine::getTable('Profile')->retrieveByName($key);
      $_result = array();
      $column = 'value';

      if ($item->isPreset())
      {
        if ($item->getFormType() === 'date')
        {
          $dateValue = $value;
          foreach ($dateValue as $k => $v)
          {
            if (!$v)
            {
              $dateValue[$k] = '%';
              continue;
            }

            if ($dateValue !== 'year')
            {
              $dateValue[$k] = sprintf('%02d', $dateValue[$k]);
            }
          }

          $value = implode('-', $dateValue);
        }
      }
      elseif ($item->getFormType() === 'date')
      {
        $options = $item->getProfileOption();
        $i = 0;
        foreach ($value as $k => $v)
        {
          $option = $options[$i++];
          if ($v)
          {
            $ids = $this->filterMemberIdByProfileOption($ids, $column, $v, $option, $publicFlag);
          }
        }
        continue;
      }
      elseif ($item->isMultipleSelect() || $item->isSingleSelect())
      {
        $column = 'profile_option_id';
      }

      $ids = $this->filterMemberIdByProfile($ids, $column, $value, $item, $publicFlag);
    }

    return $ids;
  }

  public function filterMemberIdByProfile($ids, $column, $value, Profile $item, $publicFlag = 1)
  {
    $_result = array();
    $q = Doctrine::getTable('MemberProfile')->createQuery('m');
    $q = opFormItemGenerator::filterSearchQuery($q, 'm.'.$column, $value, $item->toArray())
      ->select('m.member_id')
      ->andWhere('m.profile_id = ?', $item->getId());

    if (is_integer($publicFlag))
    {
      $publicFlags = (array)$publicFlag;
      if (1 == $publicFlag)
      {
        $publicFlags[] = 4;
      }

      if ($item->isMultipleSelect() && $item->getFormType() !== 'date')
      {
        $q->addFrom('MemberProfile pm')
          ->andWhere('m.tree_key = pm.id')
          ->andWhereIn('pm.public_flag', $publicFlags);
      }
      else
      {
        $q->andWhereIn('m.public_flag', $publicFlags);
      }
    }

    $list = $q->execute();

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

  public function filterMemberIdByProfileOption($ids, $column, $value, ProfileOption $item, $publicFlag = 1)
  {
    $_result = array();

    $q = Doctrine::getTable('MemberProfile')->createQuery('m')
      ->select('m.member_id')
      ->where('m.'.$column.'= ?', $value)
      ->andWhere('m.profile_option_id = ?', $item->getId());

    if (is_integer($publicFlag))
    {
      $q->addFrom('MemberProfile pm')
        ->andWhere('m.tree_key = pm.id')
        ->andWhere('pm.public_flag <= ?', $publicFlag);
    }

    $list = $q->execute();

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

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('everyone'))
      ->addRole(new Zend_Acl_Role('friend'), 'everyone')
      ->addRole(new Zend_Acl_Role('self'), 'friend')
      ->addRole(new Zend_Acl_Role('blocked'));
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $assertion = new opMemberProfilePublicFlagAssertion();

    return $acl
      ->allow('everyone', $resource, 'view', $assertion)
      ->allow('friend', $resource, 'view', $assertion)
      ->allow('self', $resource, 'view', $assertion)
      ->allow('self', $resource, 'edit')
      ->deny('blocked');
  }
}

class opMemberProfilePublicFlagAssertion implements Zend_Acl_Assert_Interface
{
  public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
  {
    if (ProfileTable::PUBLIC_FLAG_FRIEND == $resource->getPublicFlag())
    {
      return ($role->getRoleId() === 'self' || $role->getRoleId() === 'friend');
    }

    if (ProfileTable::PUBLIC_FLAG_PRIVATE == $resource->getPublicFlag())
    {
      return ($role->getRoleId() === 'self');
    }

    return true;
  }
}

