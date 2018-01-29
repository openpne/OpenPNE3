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

class ActivityHandler extends DataRequestHandler {
  
  private static $ACTIVITY_ID_PATH = "/activities/{userId}/{groupId}/appId/{activityId}";

  public function __construct() {
    parent::__construct('activity_service');
  }

  public function handleDelete(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ACTIVITY_ID_PATH);
    $userIds = $requestItem->getUsers();
    $activityIds = $requestItem->getListParameter("activityId");
    if (empty($userIds)) {
      throw new InvalidArgumentException("No userId specified");
    } elseif (count($userIds) > 1) {
      throw new InvalidArgumentException("Multiple userIds not supported");
    }
    return $this->service->deleteActivities($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $activityIds, $requestItem->getToken());
  }

  /**
   * /activities/{userId}/{groupId}/{optionalActvityId}
   *
   * examples:
   * /activities/john.doe/@self/1
   * /activities/john.doe/@self
   * /activities/john.doe/@friends
   */
  public function handleGet(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ACTIVITY_ID_PATH);
    $userIds = $requestItem->getUsers();
    $optionalActivityIds = $requestItem->getListParameter("activityId");
    // Preconditions
    if (empty($userIds)) {
      throw new InvalidArgumentException("No userId specified");
    } elseif (count($userIds) > 1 && ! empty($optionalActivityIds)) {
      throw new IllegalArgumentException("Cannot fetch same activityIds for multiple userIds");
    }
    if (! empty($optionalActivityIds)) {
      if (count($optionalActivityIds) == 1) {
        return $this->service->getActivity($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $requestItem->getFields(), $optionalActivityIds[0], $requestItem->getToken());
      } else {
        return $this->service->getActivities($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $requestItem->getSortBy(), $requestItem->getFilterBy(), $requestItem->getFilterOperation(), $requestItem->getFilterValue(), $requestItem->getStartIndex(), $requestItem->getCount(), $requestItem->getFields(), $optionalActivityIds, $requestItem->getToken());
      }
    }
    return $this->service->getActivities($userIds, $requestItem->getGroup(), $requestItem->getAppId(), $requestItem->getSortBy(), $requestItem->getFilterBy(), $requestItem->getFilterOperation(), $requestItem->getFilterValue(), $requestItem->getStartIndex(), $requestItem->getCount(), $requestItem->getFields(), null, $requestItem->getToken());
  }

  /**
   * /activities/{userId}/@self
   *
   * examples:
   * /activities/@viewer/@self/@app
   * /activities/john.doe/@self
   * - postBody is an activity object
   */
  public function handlePost(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ACTIVITY_ID_PATH);
    $userIds = $requestItem->getUsers();
    $activityIds = $requestItem->getListParameter("activityId");
    if (empty($userIds)) {
      throw new InvalidArgumentException("No userId specified");
    } elseif (count($userIds) > 1) {
      throw new InvalidArgumentException("Multiple userIds not supported");
    }
    // TODO This seems reasonable to allow on PUT but we don't have an update verb.
    if (! empty($activityIds)) {
      throw new InvalidArgumentException("Cannot specify activityId in create");
    }
    /*
     * Note, on just about all types of social networks you would only allow activities to be created when the owner == viewer, and the userId == viewer as well, in code this would mean:
     *  if ($token->getOwnerId() != $token->getViewerId() || $token->getViewerId() != $userId->getUserId($token)) {
     *    throw new SocialSpiException("Create activity permission denied.", ResponseError::$UNAUTHORIZED);
     *  }
     */
    return $this->service->createActivity($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $requestItem->getFields(), $requestItem->getParameter("activity"), $requestItem->getToken());
  }

  /**
   * /activities/{userId}/@self
   *
   * examples:
   * /activities/john.doe/@self
   * - postBody is an activity object
   */
  public function handlePut(RequestItem $requestItem) {
    return $this->handlePost($requestItem);
  }
}
