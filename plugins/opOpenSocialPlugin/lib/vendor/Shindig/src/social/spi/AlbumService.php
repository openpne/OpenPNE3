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
 * The interface to access albums.
 */
interface AlbumService {

  /**
   * Returns a list of Albums that correspond to the passed in User/GroupId
   *
   * @param userId ID of the user to indicate the requestor
   * @param groupId Albums for all of the people in the specific group.
   * @param albumIds album Ids to fetch. Fetch all albums if this is empty
   * @param collectionOptions options for sorting, pagination etc
   * @param fields fields to fetch
   * @param token The gadget token
   * @return a list of albums
   */
  public function getAlbums($userId, $groupId, $albumIds, $collectionOptions, $fields, $token);

  /**
   * Creates an album for a user. An Album ID is created and provided back in
   * the returned album.
   *
   * @param userId id of the user for whom an album is to be created
   * @param groupId group id for this request
   * @param album album with fields set for a create request. id field is ignored.
   * @param token security token to authorize this request
   * @return the created album with album id set in it
   */
  public function createAlbum($userId, $groupId, $album, $token);

  /**
   * Updates an album for the fields set in album.
   *
   * @param userId id of user whose album is to be updated
   * @param groupId group id for this request
   * @param album album with id and fields to be updated.
   * @param token security token to authorize this request
   * @return updated album
   */
  public function updateAlbum($userId, $groupId, $album, $token);

  /**
   * Deletes an album.
   *
   * @param userId id of owner of album
   * @param groupId group id of owner of album
   * @param albumId id of album to be deleted
   * @param token security token to authorize this request
   * @return void on completion
   */
  public function deleteAlbum($userId, $groupId, $albumId, $token);

}