<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opJsonDbOpensocialService
 *
 * @author Shogo Kawahara <kawahara@tejimaya.net>
 */
class opJsonDbOpensocialService implements ActivityService, PersonService, AppDataService, MessagesService, AlbumService, MediaItemService
{
  public function getPerson($userId, $groupId, $fields, SecurityToken $token)
  {
    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all'); 
    }
    $person = $this->getPeople($userId, $groupId, new CollectionOptions(), $fields, $token);
    if (is_array($person->getEntry()))
    {
      $person = $person->getEntry();
      if (is_array($person) && count($person) == 1)
      {
        return array_pop($person);
      }
    }
    throw new SocialSpiException("Person not found", ResponseError::$BAD_REQUEST);
  }

  public function getPeople($userId, $groupId, CollectionOptions $options, $fields, SecurityToken $token)
  {
    $ids = $this->getIdSet($userId, $groupId, $token);
    $first = $this->fixStartIndex($options->getStartIndex());
    $max   = $this->fixCount($options->getCount());
    $ret = array();

    $members = array();
    if (count($ids))
    {
      $query = Doctrine::getTable('Member')->createQuery()->whereIn('id', $ids);

      $totalSize = $query->count();

      $query->orderBy('id');
      $query->offset($first);
      $query->limit($max);

      $members = $query->execute();
    }

    $people = array();
    $viewer = (!$token->isAnonymous()) ? Doctrine::getTable('Member')->find($token->getViewerId()) : null;
    $application = ($token->getAppId()) ? Doctrine::getTable('Application')->find($token->getAppId()): null;

    $export = new opOpenSocialProfileExport();
    $export->setViewer($viewer);

    foreach ($members as $member)
    {
      $p = array();
      $p['id']       =  $member->getId();
      $p['isOwner']  =  (!$token->isAnonymous() && $member->getId() == $token->getOwnerId()) ? true : false;
      $p['isViewer'] =  (!$token->isAnonymous() && $member->getId() == $token->getViewerId()) ? true : false;
      if ($application)
      {
        $p['hasApp'] = $application->isHadByMember($member->getId());
      }
      $export->member = $member;
      $people[] = $p + $export->getData($fields);
    }

    $collection = new RestfulCollection($people, $options->getStartIndex(), $totalSize);
    $collection->setItemsPerPage($options->getCount());
    return $collection;
  }

  public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $filterOp, $filterValue, $startIndex, $count, $fields, $activityIds ,$token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getActivity($userId, $groupId, $appId, $fields, $activityId, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getPersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    if (!($userId instanceof UserId))
    {
      throw new SocialSpiException("Not support request", ResponseError::$NOT_IMPLEMENTED);
    }
    $targetUserId = (int)$userId->getUserId($token);
    $viewerId     = (int)$token->getViewerId();
    if ($targetUserId != $viewerId)
    {
      $memberRelationship = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($targetUserId, $viewerId);
      if (!($memberRelationship && $memberRelationship->isFriend()))
      {
        throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
      }
    }

    $application = Doctrine::getTable('Application')->find($appId);
    if (!$application)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    if ($groupId->getType() == 'self')
    {
      $persistentDatas = $application->getPersistentDatas($targetUserId, $fields);
    }
    else if($groupId->getType() == 'friends')
    {
      $friendIds = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($targetUserId);
      $persistentDatas = $application->getPersistentDatas($friendIds, $fields);
    }
    else
    {
      throw new SocialSpiException("We support getting data only when GROUP_ID is SELF or FRIENDS ", ResponseError::$NOT_IMPLEMENTED);
    }
    $data = array();
    if ($persistentDatas)
    {
      foreach ($persistentDatas as $persistentData)
      {
        $data[$persistentData->getMemberId()][$persistentData->getName()] = $persistentData->getValue();
      }
    }
    return new DataCollection($data);
  }

  public function deletePersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token)
  {
    if (!($userId instanceof UserId) || $userId->getUserId($token) == null )
    {
      throw new SocialSpiException("Unknown person id", ResponseError::$NOT_FOUND);
    }

    $targetUserId = $userId->getUserId($token);
    
    if ($targetUserId != $token->getViewerId())
    {
      throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
    }
    
    foreach ($fields as $key)
    {
      if (!preg_match('/[\w\-\.]+/',$key))
      {
        throw new SocialSpiException("The person app data key had in valid characters", ResponseError::$BAD_REQUEST);
      }
    }

    $application = Doctrine::getTable('Application')->find($appId);
    if (!$application)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $persistentDatas = $application->getPersistentDatas($targetUserId, $fields);
    
    foreach ($persistentDatas as $persistentData)
    {
      $persistentData->delete();
    }
  }

  public function updatePersonData(UserId $userId, GroupId $groupId, $appId, $fields, $values, SecurityToken $token)
  {
    if ($userId->getUserId($token) == null)
    {
      throw new SocialSpiException("Unknown person id", ResponseError::$NOT_FOUND);
    }

    foreach ($fields as $key)
    {
      if (!preg_match('/[\w\-\.]+/',$key))
      {
        throw new SocialSpiException("The person app data key had invalid characters", ResponseError::$BAD_REQUEST);
      }
    }

    if ($groupId->getType() != 'self')
    {
      throw new SocialSpiException("We don't support updating data in batches yet", ResponseError::$NOT_IMPLEMENTED);
    }

    $targetUserId = $userId->getUserId($token);
    $member = Doctrine::getTable('Member')->find($targetUserId);
    if (!$member)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $application = Doctrine::getTable('Application')->find($appId);
    if (!$application)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    if ($token->getOwnerId() == $targetUserId || $token->getViewerId() == $targetUserId)
    {
      $memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application, $member);
      if (!$memberApplication)
      {
        throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
      }

      foreach($fields as $name)
      {
        $value = isset($values[$name]) ? $values[$name] : null;
        $persistentData = $application->getPersistentData($targetUserId, $name);
        if (!$persistentData)
        {
          $persistentData = new ApplicationPersistentData();
          $persistentData->setApplication($application);
          $persistentData->setMember($member);
          $persistentData->setName($name);
        }
        $persistentData->setValue($value);
        $persistentData->save();
      }
    }
    else
    {
      throw new SocialSpiException("Unauthorized", ResponseError::$UNAUTHORIZED);
    }
  }

  public function createMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMessageCollection($userId, $msgCollection, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMessageCollections($userId, $fields, $options, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function createMessage($userId, $msgCollection, $message, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMessage($userId, $msgCollId, $message, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMessages($userId, $msgCollId, $messageIds, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMessages($userId, $msgCollId, $fields, $msgIds, $options, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getAlbums($userId, $groupId, $albumIds, $collectionOptions, $fields, $token)
  {
    if (!class_exists('Album'))
    {
      throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
    }

    $first = $this->fixStartIndex($collectionOptions->getStartIndex());
    $max   = $this->fixCount($collectionOptions->getCount());

    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }

    $memberIds = $this->getIdSet($userId, $groupId, $token);
    $albumIds = array_unique($albumIds);

    $objects = array();
    $totalSize = 0;

    // block check
    if ($token->getViewerId())
    {
      foreach ($memberIds as $k => $id)
      {
        $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($id, $token->getViewerId());

        if ($relation && $relation->getIsAccessBlock())
        {
          unset($memberIds[$k]);
        }
      }
    }

    if (count($memberIds))
    {
      $query = Doctrine::getTable('Album')->createQuery()
        ->whereIn('member_id', $memberIds);

      Doctrine::getTable('Album')->addPublicFlagQuery($query, AlbumTable::PUBLIC_FLAG_SNS);

      $totalSize = $query->count();

      $query->orderBy('id');
      if (count($albumIds))
      {
        $query->andWhereIn('id', $albumIds);
      }

      $query->offset($first);
      $query->limit($max);

      $objects = $query->execute();
    }
    $results = array();
    foreach ($objects as $object)
    {
      $result = array();
      $result['id'] = $object->getId();
      $result['title'] = $object->getTitle();
      $result['description'] = $object->getBody();
      $result['mediaItemCount'] = 0;
      if ($object->getAlbumImages())
      {
        $result['mediaItemCount'] = count($object->getAlbumImages());
      }
      $result['ownerId'] = $object->getMemberId();
      $result['thumbnailUrl'] = '';
      if ($object->getFile())
      {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
        $result['thumbnailUrl'] = sf_image_path($object->getFile(), array('size' => '180x180'), true);
      }
      $result['mediaType'] = 'IMAGE';
      $results[] = $result;
    }

    $collection = new RestfulCollection($results, $collectionOptions->getStartIndex(), $totalSize);
    $collection->setItemsPerPage($collectionOptions->getCount());
    return $collection;
  }

  public function createAlbum($userId, $groupId, $album, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateAlbum($userId, $groupId, $album, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteAlbum($userId, $groupId, $albumId, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function getMediaItems($userId, $groupId, $albumId, $mediaItemIds, $collectionOptions, $fields, $token)
  {
    if (!class_exists('Album'))
    {
      throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
    }

    $first = $this->fixStartIndex($collectionOptions->getStartIndex());
    $max   = $this->fixCount($collectionOptions->getCount());

    if (!is_object($userId))
    {
      $userId  = new UserId('userId', $userId);
      $groupId = new GroupId('self', 'all');
    }

    $memberIds = $this->getIdSet($userId, $groupId, $token);
    if ($groupId->getType() !== 'self' || count($memberIds) !== 1)
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }
    $memberId = $memberIds[0];

    $albumObject = Doctrine::getTable('Album')->find($albumId);
    if (!$albumObject)
    {
      throw new SocialSpiException("Album Not Found", ResponseError::$BAD_REQUEST);
    }
    if ($albumObject->getMemberId() != $memberId &&
      !($albumObject->getPublicFlag() === AlbumTable::PUBLIC_FLAG_SNS ||
      $albumObject->getPublicFlag() === AlbumTable::PUBLIC_FLAG_OPEN))
    {
      throw new SocialSpiException("Bad Request", ResponseError::$BAD_REQUEST);
    }

    $totalSize = 0;
    $query = Doctrine::getTable('AlbumImage')->createQuery()
      ->where('album_id = ?', $albumObject->getId());
    $totalSize = $query->count();

    $query->offset($first);
    $query->limit($max);

    $objects = $query->execute();

    $results = array();

    // block check
    $isBlock = false;
    if ($token->getViewerId())
    {
      $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $token->getViewerId());
      if ($relation && $relation->getIsAccessBlock())
      {
        $isBlock = true;
      }
    }

    if (!$isBlock)
    {
      foreach ($objects as $object)
      {
        $result['albumId'] = $object->getId();
        $result['created'] = $object->getCreatedAt();
        $result['description'] = $object->getDescription();
        $result['fileSize'] = $object->getFilesize();
        $result['id']       = $object->getId();
        $result['lastUpdated']  = $object->getUpdatedAt();
        $result['thumbnailUrl'] = '';
        $result['title']    = $object->getDescription();
        $result['type']     = 'IMAGE';
        $result['url']      = '';
        if ($object->getFile())
        {
          sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset', 'sfImage'));
          $result['thumbnailUrl'] = sf_image_path($object->getFile(), array('size' => '180x180'), true);
          $result['url'] = sf_image_path($object->getFile(), array(), true);
        }
        $results[] = $result;
      }
    }

    $collection = new RestfulCollection($results, $collectionOptions->getStartIndex(), $totalSize);
    $collection->setItemsPerPage($collectionOptions->getCount());
    return $collection;
  }

  public function createMediaItem($userId, $groupId, $mediaItem, $data, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function updateMediaItem($userId, $groupId, $mediaItem, $data, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMediaItems($userId, $groupId, $albumId, $mediaItemIds, $token)
  {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  protected function getIdSet($user, GroupId $group, SecurityToken $token)
  {
    $ids = array();
    if ($user instanceof UserId)
    {
      $userId = $user->getUserId($token);
      if ($group == null)
      {
        return array($userId);
      }
      switch ($group->getType())
      {
        case 'all':
        case 'friends':
        case 'groupId':
          $ids = Doctrine::getTable('MemberRelationship')->getFriendMemberIds($userId);
          break;
        case 'self':
          $ids[] = $userId;
          break;
      }
    }
    elseif (is_array($user))
    {
      $ids = array();
      foreach ($user as $id)
      {
        $ids = array_merge($ids, $this->getIdSet($id, $group, $token));
      }
    }
    return $ids;
  }

  protected function fixStartIndex($startIndex = null)
  {
    if (!($startIndex !== false && is_numeric($startIndex) && $startIndex >= 0))
    {
      return 0;
    }

    return $startIndex;
  }

  protected function fixCount($count = null)
  {
    if (!($count !== false && is_numeric($count) && $count > 0))
    {
      return RequestItem::$DEFAULT_COUNT;
    }

    if ($count > sfConfig::get('op_opensocial_api_max_count', 100))
    {
      return sfConfig::get('op_opensocial_api_max_count', 100);
    }

    return $count;
  }
}
