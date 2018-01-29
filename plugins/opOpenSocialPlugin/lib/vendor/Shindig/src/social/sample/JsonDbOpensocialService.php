<?php

/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/**
 * Implementation of supported services backed by a JSON DB
 */
class JsonDbOpensocialService implements ActivityService, PersonService, AppDataService, MessagesService, AlbumService, MediaItemService {

  /**
   * The DB
   */
  private $db;

  /**
   * db["activities"] -> Array<Person>
   */
  private static $PEOPLE_TABLE = "people";

  /**
   * db["people"] -> Map<Person.Id, Array<Activity>>
   */
  private static $ACTIVITIES_TABLE = "activities";

  /**
   * db["messages"] : Map<Person.Id, MessageCollection>
   */
  private static $MESSAGES_TABLE = "messages";

  /**
   * db["albums"] -> Map<Person.Id, Map<Album.Id, Album>>
   */
  private static $ALBUMS_TABLE = "albums";

  /**
   * db["mediaItems"] -> Map<Album.Id, Map<MediaItem.Id, MediaItem>>
   */
  private static $MEDIA_ITEMS_TABLE = "mediaItems";

  /**
   * db["data"] -> Map<Person.Id, Map<String, String>>
   */
  private static $DATA_TABLE = "data";

  /**
   * db["friendLinks"] -> Map<Person.Id, Array<Person.Id>>
   */
  private static $FRIEND_LINK_TABLE = "friendLinks";

  /**
   * db["userApplications"] -> Map<Person.Id, Array<Application Ids>>
   */
  private static $USER_APPLICATIONS_TABLE = "userApplications";

  private $allPeople = null;

  private $allData = null;

  private $allActivities = null;

  private $allMessageCollections = null;

  private $jsonDbFileName = 'ShindigDb.json';

  public function getDb() {
    try {
      $fileName = sys_get_temp_dir() . '/' . $this->jsonDbFileName;
      if (file_exists($fileName)) {
        if (! is_readable($fileName)) {
          throw new SocialSpiException("Could not read temp json db file: $fileName, check permissions", ResponseError::$INTERNAL_ERROR);
        }
        $cachedDb = file_get_contents($fileName);
        $jsonDecoded = json_decode($cachedDb, true);
        if ($jsonDecoded == $cachedDb || $jsonDecoded == null) {
          throw new SocialSpiException("Failed to decode the json db", ResponseError::$INTERNAL_ERROR);
        }
        return $jsonDecoded;
      } else {
        $jsonDb = Shindig_Config::get('jsondb_path');
        if (! file_exists($jsonDb) || ! is_readable($jsonDb)) {
          throw new SocialSpiException("Could not read json db file: $jsonDb, check if the file exists & has proper permissions", ResponseError::$INTERNAL_ERROR);
        }
        $dbConfig = @file_get_contents($jsonDb);
        $contents = preg_replace('/(?<!http:|https:)\/\/.*$/m', '', preg_replace('@/\\*.*?\\*/@s', '', $dbConfig));
        $jsonDecoded = json_decode($contents, true);
        if ($jsonDecoded == $contents || $jsonDecoded == null) {
          throw new SocialSpiException("Failed to decode the json db", ResponseError::$INTERNAL_ERROR);
        }
        $this->saveDb($jsonDecoded);
        return $jsonDecoded;
      }
    } catch (Exception $e) {
      throw new SocialSpiException("An error occured while reading/writing the json db: " . $e->getMessage(), ResponseError::$INTERNAL_ERROR);
    }
  }

  private function saveDb($db) {
    if (! @file_put_contents(sys_get_temp_dir() . '/' . $this->jsonDbFileName, json_encode($db))) {
      throw new Exception("Could not save json db: " . sys_get_temp_dir() . '/' . $this->jsonDbFileName);
    }
  }

  private function getAllPeople() {
    $db = $this->getDb();
    $peopleTable = $db[self::$PEOPLE_TABLE];
    foreach ($peopleTable as $people) {
      $this->allPeople[$people['id']] = $people;
    }
    $db[self::$PEOPLE_TABLE] = $this->allPeople;
    return $this->allPeople;
  }

  private function getAllData() {
    $db = $this->getDb();
    $dataTable = $db[self::$DATA_TABLE];
    foreach ($dataTable as $key => $value) {
      $this->allData[$key] = $value;
    }
    $db[self::$DATA_TABLE] = $this->allData;
    return $this->allData;
  }

  private function getAllActivities() {
    $db = $this->getDb();
    $activitiesTable = $db[self::$ACTIVITIES_TABLE];
    foreach ($activitiesTable as $key => $value) {
      $this->allActivities[$key] = $value;
    }
    $db[self::$ACTIVITIES_TABLE] = $this->allActivities;
    return $this->allActivities;
  }

  private function getAllMessageCollections() {
    $db = $this->getDb();
    $messagesTable = $db[self::$MESSAGES_TABLE];
    foreach ($messagesTable as $key => $value) {
      $this->allMessageCollections[$key] = $value;
    }
    $db[self::$MESSAGES_TABLE] = $this->allMessageCollections;
    return $this->allMessageCollections;
  }

  private function getAllAlbums() {
    $db = $this->getDb();
    $albumTable = $db[self::$ALBUMS_TABLE] ? $db[self::$ALBUMS_TABLE] : array();
    $allAlbums = array();
    foreach ($albumTable as $key => $value) {
      $allAlbums[$key] = $value;
    }
    return $allAlbums;
  }

  private function getAllMediaItems() {
    $db = $this->getDb();
    $mediaItemsTable = $db[self::$MEDIA_ITEMS_TABLE] ? $db[self::$MEDIA_ITEMS_TABLE] : array();
    $allMediaItems = array();
    foreach ($mediaItemsTable as $key => $value) {
      $allMediaItems[$key] = $value;
    }
    return $allMediaItems;
  }

  private function getPeopleWithApp($appId) {
    $peopleWithApp = array();
    $db = $this->getDb();
    $userApplicationsTable = $db[self::$USER_APPLICATIONS_TABLE];
    foreach ($userApplicationsTable as $key => $value) {
      if (in_array($appId, $userApplicationsTable[$key])) {
        $peopleWithApp[] = $key;
      }
    }
    return $peopleWithApp;
  }

  public function getPerson($userId, $groupId, $fields, SecurityToken $token) {
    if (! is_object($groupId)) {
      throw new SocialSpiException("Not Implemented", ResponseError::$NOT_IMPLEMENTED);
    }
    $person = $this->getPeople($userId, $groupId, new CollectionOptions(), $fields, $token);
    if (is_array($person->getEntry())) {
      $person = $person->getEntry();
      if (is_array($person) && count($person) == 1) {
        return array_pop($person);
      }
    }
    throw new SocialSpiException("Person not found", ResponseError::$BAD_REQUEST);
  }

  private function getMutualFriends($ids, $friendId) {
    $db = $this->getDb();
    $friendsTable = $db[self::$FRIEND_LINK_TABLE];
    if (is_array($friendsTable) && count($friendsTable) && isset($friendsTable[$friendId])) {
      $friendIds = $friendsTable[$friendId];
      $mutualFriends = array_intersect($ids, $friendIds);
    }
    return $mutualFriends;
  }

  public function getPeople($userId, $groupId, CollectionOptions $options, $fields, SecurityToken $token) {
    $sortOrder = $options->getSortOrder();
    $filter = $options->getFilterBy();
    $filterOp = $options->getFilterOperation();
    $filterValue = $options->getFilterValue();
    $first = $options->getStartIndex();
    $max = $options->getCount();
    $networkDistance = $options->getNetworkDistance();
    $ids = $this->getIdSet($userId, $groupId, $token);
    $allPeople = $this->getAllPeople();
    if ($filter == "@friends" && $filterOp == "contains" && isset($filterValue)) {
      if ($options->getFilterValue() == '@viewer') {
        $filterValue = $token->getViewerId();
      } elseif ($options->getFilterValue() == '@owner') {
        $filterValue = $token->getOwnerId();
      }
      $ids = $this->getMutualFriends($ids, $filterValue);
    }
    if (! $token->isAnonymous() && $filter == "hasApp") {
      $appId = $token->getAppId();
      $peopleWithApp = $this->getPeopleWithApp($appId);
    }
    $people = array();
    foreach ($ids as $id) {
      if ($filter == "hasApp" && ! in_array($id, $peopleWithApp)) {
        continue;
      }
      $person = null;
      if (is_array($allPeople) && isset($allPeople[$id])) {
        $person = $allPeople[$id];
        if (! $token->isAnonymous() && $id == $token->getViewerId()) {
          $person['isViewer'] = true;
        }
        if (! $token->isAnonymous() && $id == $token->getOwnerId()) {
          $person['isOwner'] = true;
        }
        if ($fields[0] != '@all') {
          $newPerson = array();
          $newPerson['isOwner'] = isset($person['isOwner']) ? $person['isOwner'] : false;
          $newPerson['isViewer'] = isset($person['isViewer']) ? $person['isViewer'] : false;
          $newPerson['name'] = $person['name'];
          $newPerson['displayName'] = $person['displayName'];
          foreach ($fields as $field => $present) {
            $present = strtolower($present);
            if (isset($person[$present]) && ! isset($newPerson[$present])) {
              $newPerson[$present] = $person[$present];
            }
          }
          $person = $newPerson;
        }
        $people[$id] = $person;
      }
    }
    if ($sortOrder == 'name') {
      usort($people, array($this, 'comparator'));
    }

    try {
      $people = $this->filterResults($people, $options);
    } catch (Exception $e) {
      $people['filtered'] = 'false';
    }

    //TODO: The samplecontainer doesn't support any filters yet. We should fix this.
    $totalSize = count($people);
    $collection = new RestfulCollection($people, $options->getStartIndex(), $totalSize);
    $collection->setItemsPerPage($options->getCount());
    return $collection;
  }

  private function filterResults($results, $options) {
    if (! $options->getFilterBy()) {
      return $results; // no filtering specified
    }
    $filterBy = $options->getFilterBy();
    $op = $options->getFilterOperation();
    if (! $op) {
      $op = CollectionOptions::FILTER_OP_EQUALS; // use this container-specific default
    }
    $value = $options->getFilterValue();
    $filteredResults = array();
    $numFilteredResults = 0;
    foreach ($results as $id => $person) {
      if ($this->passesFilter($person, $filterBy, $op, $value)) {
        $filteredResults[$id] = $person;
        $numFilteredResults ++;
      }
    }
    return $filteredResults;
  }

  private function passesFilter($entity, $filterBy, $op, $value) {
    $fieldValue = $entity[$filterBy];
    if (! $fieldValue || (is_array($fieldValue) && ! count($fieldValue))) {
      return false; // person is missing the field being filtered for
    }
    if ($op == CollectionOptions::FILTER_OP_PRESENT) {
      return true; // person has a non-empty value for the requested field
    }
    if (! $value) {
      return false; // can't do an equals/startswith/contains filter on an empty filter value
    }
    // grab string value for comparison
    if (is_array($fieldValue)) {
      // plural fields match if any instance of that field matches
      foreach ($fieldValue as $field) {
        if ($this->passesStringFilter($field, $op, $value)) {
          return true;
        }
      }
    } else {
      return $this->passesStringFilter($fieldValue, $op, $value);
    }

    return false;
  }

  public function getPersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token) {
    if (! isset($fields[0])) {
      $fields[0] = '@all';
    }
    $db = $this->getDb();
    $allData = $this->getAllData();
    $friendsTable = $db[self::$FRIEND_LINK_TABLE];
    $data = array();
    $ids = $this->getIdSet($userId, $groupId, $token);
    foreach ($ids as $id) {
      if (isset($allData[$id])) {
        $allPersonData = $allData[$id];
        $personData = array();
        foreach (array_keys($allPersonData) as $key) {
          if (in_array($key, $fields) || in_array("@all", $fields)) {
            $personData[$key] = $allPersonData[$key];
          }
        }
        $data[$id] = $personData;
      }
    }
    return new DataCollection($data);
  }

  public function updatePersonData(UserId $userId, GroupId $groupId, $appId, $fields, $values, SecurityToken $token) {
    $db = $this->getDb();
    foreach ($fields as $key => $present) {
      if (! $this->isValidKey($present)) {
        throw new SocialSpiException("The person app data key had invalid characters", ResponseError::$BAD_REQUEST);
      }
    }
    $allData = $this->getAllData();
    $person = $allData[$userId->getUserId($token)];
    switch ($groupId->getType()) {
      case 'self':
        foreach ($fields as $key => $present) {
          $value = isset($values[$present]) ? @$values[$present] : null;
          $person[$present] = $value;
        }
        break;
      default:
        throw new SocialSpiException("We don't support updating data in batches yet", ResponseError::$NOT_IMPLEMENTED);
        break;
    }
    $allData[$userId->getUserId($token)] = $person;
    $db[self::$DATA_TABLE] = $allData;
    $this->saveDb($db);
    return null;
  }

  public function deletePersonData($userId, GroupId $groupId, $appId, $fields, SecurityToken $token) {
    $db = $this->getDb();
    $allData = $this->getAllData();
    if ($fields == null || $fields[0] == '*') {
      $allData[$userId->getUserId($token)] = null;
      $db[self::$DATA_TABLE] = $allData;
      $this->saveDb($db);
      return null;
    }
    foreach ($fields as $key => $present) {
      if (! $this->isValidKey($key)) {
        throw new SocialSpiException("The person app data key had invalid characters", ResponseError::$BAD_REQUEST);
      }
    }
    switch ($groupId->getType()) {
      case 'self':
        foreach ($fields as $key => $present) {
          $value = isset($values[$key]) ? null : @$values[$key];
          $person[$key] = $value;
        }
        $allData[$userId->getUserId($token)] = $person;
        $db[self::$DATA_TABLE] = $allData;
        $this->saveDb($db);
        break;
      default:
        throw new SocialSpiException("We don't support updating data in batches yet", ResponseError::$NOT_IMPLEMENTED);
        break;
    }
    return null;
  }

  public function getActivity($userId, $groupId, $appdId, $fields, $activityId, SecurityToken $token) {
    $activities = $this->getActivities($userId, $groupId, $appdId, null, null, null, null, $fields, array(
        $activityId), $token);
    if ($activities instanceof RestfulCollection) {
      $activities = $activities->getEntry();
      foreach ($activities as $activity) {
        if ($activity->getId() == $activityId) {
          return $activity;
        }
      }
    }
    throw new SocialSpiException("Activity not found", ResponseError::$NOT_FOUND);
  }

  public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $filterOp, $filterValue, $startIndex, $count, $fields, $activityIds, $token) {
    $db = $this->getDb();
    $friendsTable = $db[self::$FRIEND_LINK_TABLE];
    $ids = array();
    $ids = $this->getIdSet($userIds, $groupId, $token);
    $allActivities = $this->getAllActivities();
    $activities = array();
    foreach ($ids as $id) {
      if (isset($allActivities[$id])) {
        $personsActivities = $allActivities[$id];
        $activities = array_merge($activities, $personsActivities);
        if ($fields) {
          $newPersonsActivities = array();
          foreach ($personsActivities as $activity) {
            $newActivity = array();
            foreach ($fields as $field => $present) {
              $newActivity[$present] = $activity[$present];
            }
            $newPersonsActivities[] = $newActivity;
          }
          $personsActivities = $newPersonsActivities;
          $activities = $personsActivities;
        }
        if ($filterBy && $filterValue) {
          $newActivities = array();
          foreach ($activities as $activity) {
            if (array_key_exists($filterBy, $activity)) {
              if ($this->passesStringFilter($activity[$filterBy], $filterOp, $filterValue)) {
                $newActivities[] = $activity;
              }
            } else {
              throw new SocialSpiException("Invalid filterby parameter", ResponseError::$NOT_FOUND);
            }
          }
          $activities = $newActivities;
        }
      }
    }
    $totalResults = count($activities);
    if (! $totalResults) {
      throw new SocialSpiException("Activity not found", ResponseError::$NOT_FOUND);
    }
    $activities = array_slice($activities, $startIndex, $count);
    $ret = new RestfulCollection($activities, $startIndex, $totalResults);
    $ret->setItemsPerPage($count);
    return $ret;
  }

  /*
   * to check the activity against filter
   */
  private function passesStringFilter($fieldValue, $filterOp, $filterValue) {
    switch ($filterOp) {
      case CollectionOptions::FILTER_OP_EQUALS:
        return $fieldValue == $filterValue;
      case CollectionOptions::FILTER_OP_CONTAINS:
        return strpos($fieldValue, $filterValue) !== false;
      case CollectionOptions::FILTER_OP_STARTSWITH:
        return strpos($fieldValue, $filterValue) === 0;
      default:
        throw new Exception('unrecognized filterOp');
    }
  }

  public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token) {
    $db = $this->getDb();
    $activitiesTable = $this->getAllActivities();
    $activity['appId'] = $token->getAppId();
    try {
      if (! isset($activitiesTable[$userId->getUserId($token)])) {
        $activitiesTable[$userId->getUserId($token)] = array();
      }
      $activity['id'] = count($activitiesTable[$userId->getUserId($token)]) + 1;
      array_push($activitiesTable[$userId->getUserId($token)], $activity);
      $db[self::$ACTIVITIES_TABLE] = $activitiesTable;
      $this->saveDb($db);
      // Should this return something to show success?
    } catch (Exception $e) {
      throw new SocialSpiException("Activity can't be created: " . $e->getMessage(), ResponseError::$INTERNAL_ERROR);
    }
  }

  public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token) {
    $db = $this->getDb();
    $activitiesTable = $this->getAllActivities();
    if (! isset($activitiesTable[$userId->getUserId($token)])) {
      throw new SocialSpiException("Activity not found.", ResponseError::$BAD_REQUEST);
    }
    $newActivities = array();
    foreach ($activitiesTable[$userId->getUserId($token)] as $activity) {
      $found = false;
      foreach ($activityIds as $id) {
        if ($activity['id'] == $id) {
          $found = true;
        }
      }
      if (! $found) {
        array_push($newActivities, $activity);
      }
    }
    if (count($newActivities) == count($activitiesTable[$userId->getUserId($token)])) {
      throw new SocialSpiException("Activities not found.", ResponseError::$BAD_REQUEST);
    }
    $activitiesTable[$userId->getUserId($token)] = $newActivities;
    $db[self::$ACTIVITIES_TABLE] = $activitiesTable;
    $this->saveDb($db);
  }

  public function createMessage($userId, $msgCollId, $message, $token) {
    $db = $this->getDb();
    $messagesTable = $this->getAllMessageCollections();
    if ($msgCollId == '@outbox') {
      $msgCollId = 'privateMessage';
    }
    if (! isset($messagesTable[$userId->getUserId($token)]) || ! isset($messagesTable[$userId->getUserId($token)][$msgCollId])) {
      throw new SocialSpiException("Message collection not found.", ResponseError::$BAD_REQUEST);
    }
    $msgColl = $messagesTable[$userId->getUserId($token)][$msgCollId];
    if (! isset($msgColl['messages'])) {
      $msgColl['messages'] = array();
    }
    $message['id'] = count($msgColl['messages']) + 1;
    $msgColl['messages'][$message['id']] = $message;
    if (isset($msgColl['total'])) {
      ++ $msgColl['total'];
    } else {
      $msgColl['total'] = 1;
    }
    if (isset($msgColl['unread'])) {
      ++ $msgColl['unread'];
    } else {
      $msgColl['unread'] = 1;
    }
    $messagesTable[$userId->getUserId($token)][$msgCollId] = $msgColl;
    $db[self::$MESSAGES_TABLE] = $messagesTable;
    $this->saveDb($db);
  }

  public function updateMessage($userId, $msgCollId, $message, $token) {
    throw new SocialSpiException("Not implemented", ResponseError::$NOT_IMPLEMENTED);
  }

  public function deleteMessages($userId, $msgCollId, $messageIds, $token) {
    $db = $this->getDb();
    $messagesTable = $this->getAllMessageCollections();
    if ($msgCollId == '@inbox' || $msgCollId == '@outbox') {
      $msgCollId = 'privateMessage';
    }
    if (! isset($messagesTable[$userId->getUserId($token)]) || ! isset($messagesTable[$userId->getUserId($token)][$msgCollId])) {
      throw new SocialSpiException("Message collection not found.", ResponseError::$BAD_REQUEST);
    }
    $msgColl = $messagesTable[$userId->getUserId($token)][$msgCollId];
    foreach ($messageIds as $id) {
      if (! isset($msgColl['messages']) || ! isset($msgColl['messages'][$id])) {
        throw new SocialSpiException("Message not found.", ResponseError::$BAD_REQUEST);
      }
    }
    foreach ($messageIds as $id) {
      unset($msgColl['messages'][$id]);
    }
    if (isset($msgColl['total'])) {
      $msgColl['total'] -= count($messageIds);
    }
    $messagesTable[$userId->getUserId($token)][$msgCollId] = $msgColl;
    $db[self::$MESSAGES_TABLE] = $messagesTable;
    $this->saveDb($db);
  }

  public function getMessages($userId, $msgCollId, $fields, $msgIds, $options, $token) {
    $collections = $this->getAllMessageCollections();
    $results = array();
    // TODO: Handles @inbox and @outbox.
    if ($msgCollId == '@outbox' || $msgCollId == '@inbox') {
      $msgCollId = 'privateMessage';
    }
    if (isset($collections[$userId->getUserId($token)]) && isset($collections[$userId->getUserId($token)][$msgCollId])) {
      $msgColl = $collections[$userId->getUserId($token)][$msgCollId];
      if (! isset($msgColl['messages'])) {
        $msgColl['messages'] = array();
      }
      if (empty($msgIds)) {
        $results = $msgColl['messages'];
      } else {
        foreach ($msgColl['messages'] as $message) {
          if (in_array($message['id'], $msgIds)) {
            $results[] = $message;
          }
        }
      }
      if ($options) {
        $results = $this->filterResults($results, $options);
      }
      if ($fields) {
        $results = self::adjustFields($results, $fields);
      }
      return self::paginateResults($results, $options);
    } else {
      throw new SocialSpiException("Message collections not found", ResponseError::$NOT_FOUND);
    }
  }

  public function createMessageCollection($userId, $msgCollection, $token) {
    $db = $this->getDb();
    $messagesTable = $this->getAllMessageCollections();
    try {
      if (! isset($messagesTable[$userId->getUserId($token)])) {
        $messagesTable[$userId->getUserId($token)] = array();
      } else if (isset($messagesTable[$userId->getUserId($token)][$msgCollection['id']])) {
        throw new SocialSpiException("Message collection already exists.", ResponseError::$BAD_REQUEST);
      }
      $msgCollection['total'] = 0;
      $msgCollection['unread'] = 0;
      $msgCollection['updated'] = time();
      $id = count($messagesTable[$userId->getUserId($token)]);
      $msgCollection['id'] = $id;
      $messagesTable[$userId->getUserId($token)][$id] = $msgCollection;
      $db[self::$MESSAGES_TABLE] = $messagesTable;
      $this->saveDb($db);
      return $msgCollection;
    } catch (Exception $e) {
      throw new SocialSpiException("Message collection can't be created: " . $e->getMessage(), ResponseError::$INTERNAL_ERROR);
    }
  }

  public function updateMessageCollection($userId, $msgCollection, $token) {
    $db = $this->getDb();
    $messagesTable = $this->getAllMessageCollections();
    if (! isset($messagesTable[$userId->getUserId($token)]) || ! isset($messagesTable[$userId->getUserId($token)][$msgCollection['id']])) {
      throw new SocialSpiException("Message collection not found.", ResponseError::$BAD_REQUEST);
    }
    // The total number of messages in the collection shouldn't be updated.
    $msgCollection['total'] = $messagesTable[$userId->getUserId($token)][$msgCollection['id']]['total'];
    $msgCollection['updated'] = time();
    $messagesTable[$userId->getUserId($token)][$msgCollection['id']] = $msgCollection;
    $db[self::$MESSAGES_TABLE] = $messagesTable;
    $this->saveDb($db);
  }

  public function deleteMessageCollection($userId, $msgCollId, $token) {
    $db = $this->getDb();
    $messagesTable = $this->getAllMessageCollections();
    try {
      if (! isset($messagesTable[$userId->getUserId($token)]) || ! isset($messagesTable[$userId->getUserId($token)][$msgCollId])) {
        throw new SocialSpiException("Message collection not found.", ResponseError::$NOT_FOUND);
      } else {
        unset($messagesTable[$userId->getUserId($token)][$msgCollId]);
      }
      $db[self::$MESSAGES_TABLE] = $messagesTable;
      $this->saveDb($db);
    } catch (Exception $e) {
      throw new SocialSpiException("Message collection can't be created: " . $e->getMessage(), ResponseError::$INTERNAL_ERROR);
    }
  }

  public function getMessageCollections($userId, $fields, $options, $token) {
    $all = $this->getAllMessageCollections();
    $results = array();
    if (isset($all[$userId->getUserId($token)])) {
      $results = $all[$userId->getUserId($token)];
    } else {
      return RestfulCollection::createFromEntry(array());
    }
    if ($options) {
      $results = $this->filterResults($results, $options);
    }
    if (empty($results)) {
      throw new SocialSpiException("Message collections not found", ResponseError::$NOT_FOUND);
    }
    foreach ($results as $id => $messageCollection) {
      if (! isset($results[$id]["id"])) {
        $results[$id]["id"] = $id;
      }
      $results[$id]["total"] = isset($results[$id]["messages"]) ? count($results[$id]["messages"]) : 0;
      $results[$id]["unread"] = $results[$id]["total"];
    }
    if ($fields) {
      $results = self::adjustFields($results, $fields);
    }
    return self::paginateResults($results, $options);
  }

  public function getAlbums($userId, $groupId, $albumIds, $options, $fields, $token) {
    $all = $this->getAllAlbums();
    $allMediaItems = $this->getAllMediaItems();
    $results = array();
    if (! isset($all[$userId->getUserId($token)])) {
      return RestfulCollection::createFromEntry(array());
    }
    $albumIds = array_unique($albumIds);
    foreach ($all[$userId->getUserId($token)] as $id => $album) {
      if (empty($albumIds) || in_array($id, $albumIds)) {
        $results[] = $album;
        $album['mediaItemCount'] = count($allMediaItems[$id]);
      }
    }
    if ($options) {
      $results = $this->filterResults($results, $options);
    }
    if ($fields) {
      $results = self::adjustFields($results, $fields);
    }
    return self::paginateResults($results, $options);
  }

  public function createAlbum($userId, $groupId, $album, $token) {
    $all = $this->getAllAlbums();
    $cnt = 0;
    foreach ($all as $key => $value) {
      $cnt += count($value);
    }
    $id = 'testIdPrefix' . $cnt;
    $album['id'] = $id;
    $album['ownerId'] = $userId->getUserId($token);
    if (isset($album['mediaType'])) {
      $album['mediaType'] = strtoupper($album['mediaType']);
      if (! in_array($album['mediaType'], MediaItem::$TYPES)) {
        unset($album['mediaType']);
      }
    }
    if (! isset($all[$userId->getUserId($token)])) {
      $all[$userId->getUserId($token)] = array();
    }
    $all[$userId->getUserId($token)][$id] = $album;
    $db = $this->getDb();
    $db[self::$ALBUMS_TABLE] = $all;
    $this->saveDb($db);
    return $album;
  }

  public function updateAlbum($userId, $groupId, $album, $token) {
    $all = $this->getAllAlbums();
    if (! $all[$userId->getUserId($token)] || ! $all[$userId->getUserId($token)][$album['id']]) {
      throw new SocialSpiException("Album not found.", ResponseError::$BAD_REQUEST);
    }
    $origin = $all[$userId->getUserId($token)][$album['id']];
    if ($origin['ownerId'] != $userId->getUserId($token)) {
      throw new SocialSpiException("Not the owner.", ResponseError::$UNAUTHORIZED);
    }
    $album['ownerId'] = $origin['ownerId'];
    if (isset($album['mediaType'])) {
      $album['mediaType'] = strtoupper($album['mediaType']);
      if (! in_array($album['mediaType'], MediaItem::$TYPES)) {
        unset($album['mediaType']);
      }
    }
    $all[$userId->getUserId($token)][$album['id']] = $album;

    $db = $this->getDb();
    $db[self::$ALBUMS_TABLE] = $all;
    $this->saveDb($db);
  }

  public function deleteAlbum($userId, $groupId, $albumId, $token) {
    $all = $this->getAllAlbums();
    if (! $all[$userId->getUserId($token)] || ! $all[$userId->getUserId($token)][$albumId]) {
      throw new SocialSpiException("Album not found.", ResponseError::$BAD_REQUEST);
    }
    if ($all[$userId->getUserId($token)][$albumId]['ownerId'] != $userId->getUserId($token)) {
      throw new SocialSpiException("Not the owner.", ResponseError::$UNAUTHORIZED);
    }
    unset($all[$userId->getUserId($token)][$albumId]);
    $db = $this->getDb();
    $db[self::$ALBUMS_TABLE] = $all;
    $this->saveDb($db);
  }

  public function getMediaItems($userId, $groupId, $albumId, $mediaItemIds, $options, $fields, $token) {
    $all = $this->getAllMediaItems();
    $results = array();
    if (! isset($all[$albumId])) {
      return RestfulCollection::createFromEntry(array());
    }
    $mediaItemIds = array_unique($mediaItemIds);
    foreach ($all[$albumId] as $id => $mediaItem) {
      if (empty($mediaItemIds) || in_array($id, $mediaItemIds)) {
        $results[] = $mediaItem;
      }
    }
    if ($options) {
      $results = $this->filterResults($results, $options);
    }
    if ($fields) {
      $results = self::adjustFields($results, $fields);
    }
    return self::paginateResults($results, $options);
  }

  public function createMediaItem($userId, $groupId, $mediaItem, $data, $token) {
    $all = $this->getAllMediaItems();
    $albumId = $mediaItem['albumId'];
    $id = count($all[$albumId]) + 1;
    $mediaItem['id'] = $id;
    $mediaItem['lastUpdated'] = time();
    if (isset($mediaItem['type'])) {
      $mediaItem['type'] = strtoupper($mediaItem['type']);
      if (! in_array($mediaItem['type'], MediaItem::$TYPES)) {
        unset($mediaItem['type']);
      }
    }
    if (! $all[$albumId]) {
      $all[$albumId] = array();
    }
    $all[$albumId][$id] = $mediaItem;
    $db = $this->getDb();
    $db[self::$MEDIA_ITEMS_TABLE] = $all;
    $this->saveDb($db);
    return $mediaItem;
  }

  public function updateMediaItem($userId, $groupId, $mediaItem, $data, $token) {
    $all = $this->getAllMediaItems();
    if (! $all[$mediaItem['albumId']] || ! $all[$mediaItem['albumId']][$mediaItem['id']]) {
      throw new SocialSpiException("MediaItem not found.", ResponseError::$BAD_REQUEST);
    }

    $origin = $all[$mediaItem['albumId']][$mediaItem['id']];
    $mediaItem['lastUpdated'] = time();
    $mediaItem['created'] = $origin['created'];
    $mediaItem['fileSize'] = $orgin['fileSize'];
    $mediaItem['numComments'] = $origin['numComments'];
    if (isset($mediaItem['type'])) {
      $mediaItem['type'] = strtoupper($mediaItem['type']);
      if (! in_array($mediaItem['type'], MediaItem::$TYPES)) {
        unset($mediaItem['type']);
      }
    }

    $all[$mediaItem['albumId']][$mediaItem['id']] = $mediaItem;
    $db = $this->getDb();
    $db[self::$MEDIA_ITEMS_TABLE] = $all;
    $this->saveDb($db);
  }

  public function deleteMediaItems($userId, $groupId, $albumId, $mediaItemIds, $token) {
    $all = $this->getAllMediaItems();
    if (! $all[$albumId]) {
      throw new SocialSpiException("MediaItem not found.", ResponseError::$BAD_REQUEST);
    }
    foreach ($mediaItemIds as $id) {
      if (! $all[$albumId][$id]) {
        throw new SocialSpiException("MediaItem not found.", ResponseError::$BAD_REQUEST);
      }
    }
    foreach ($mediaItemIds as $id) {
      unset($all[$albumId][$id]);
    }
    $db = $this->getDb();
    $db[self::$MEDIA_ITEMS_TABLE] = $all;
    $this->saveDb($db);
  }

  /**
   * Paginates the results set according to the critera specified by the options.
   */
  private static function paginateResults($results, $options) {
    if (! $options) {
      return RestfulCollection::createFromEntry($results);
    } else {
      $startIndex = $options->getStartIndex();
      $count = $options->getCount();
      $totalResults = count($results);
      // NOTE: Assumes the index is 0 based.
      $results = array_slice($results, $startIndex, $count);
      $ret = new RestfulCollection($results, $startIndex, $totalResults);
      $ret->setItemsPerPage($count);
      return $ret;
    }
  }

  /**
   * Removes the unnecessary fields by sets the requested fiedls only.
   */
  private static function adjustFields($results, $fields) {
    if (empty($fields) || empty($results) || in_array('@all', $fields)) {
      return $results;
    }
    $newResults = array();
    foreach ($results as $entity) {
      $newEntity = array();
      foreach ($fields as $field) {
        $newEntity[$field] = isset($entity[$field]) ? $entity[$field] : null;
      }
      $newResults[] = $newEntity;
    }
    return $newResults;
  }

  /**
   * Determines whether the input is a valid key.
   *
   * @param key the key to validate.
   * @return true if the key is a valid appdata key, false otherwise.
   */
  public static function isValidKey($key) {
    if (empty($key)) {
      return false;
    }
    for ($i = 0; $i < strlen($key); ++ $i) {
      $c = substr($key, $i, 1);
      if (($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || ($c >= '0' && $c <= '9') || ($c == '-') || ($c == '_') || ($c == '.')) {
        continue;
      }
      return false;
    }
    return true;
  }

  private function comparator($person, $person1) {
    $name = $person['name']['unstructured'];
    $name1 = $person1['name']['unstructured'];
    if ($name == $name1) {
      return 0;
    }
    return ($name < $name1) ? - 1 : 1;
  }

  /**
   * Get the set of user id's from a user or collection of users, and group
   * Code taken from http://code.google.com/p/partuza/source/browse/trunk/Shindig/PartuzaService.php
   */
  private function getIdSet($user, GroupId $group, SecurityToken $token) {
    $ids = array();
    $db = $this->getDb();
    $friendsTable = $db[self::$FRIEND_LINK_TABLE];
    if ($user instanceof UserId) {
      $userId = $user->getUserId($token);
      if ($group == null) {
        return array($userId);
      }
      switch ($group->getType()) {
        case 'self':
          $ids[] = $userId;
          break;
        case 'all':
        case 'friends':
          if (is_array($friendsTable) && count($friendsTable) && isset($friendsTable[$userId])) {
            $ids = $friendsTable[$userId];
          }
          break;
        default:
          return new ResponseItem(NOT_IMPLEMENTED, "We don't support fetching data in batches yet", null);
          break;
      }
    } elseif (is_array($user)) {
      $ids = array();
      foreach ($user as $id) {
        $ids = array_merge($ids, $this->getIdSet($id, $group, $token));
      }
    }
    return $ids;
  }
}
