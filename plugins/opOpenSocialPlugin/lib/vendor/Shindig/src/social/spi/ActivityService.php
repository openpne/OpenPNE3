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

interface ActivityService {

  /**
   * Returns a list of activities that correspond to the passed in person ids.
   */
  public function getActivities($userIds, $groupId, $appId, $sortBy, $filterBy, $filterOp, $filterValue, $startIndex, $count, $fields, $activityIds, $token);

  public function getActivity($userId, $groupId, $appdId, $fields, $activityId, SecurityToken $token);

  public function deleteActivities($userId, $groupId, $appId, $activityIds, SecurityToken $token);

  /**
   * Creates the passed in activity for the given user. Once createActivity is
   * called, getActivities will be able to return the Activity.
   */
  public function createActivity($userId, $groupId, $appId, $fields, $activity, SecurityToken $token);
}
