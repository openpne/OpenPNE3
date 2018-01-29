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
 * AlbumHandler checks POST/PUT/GET/DELETE requests params and call the album service.
 */
class AlbumHandler extends DataRequestHandler {
  private static $ALBUM_PATH = "/albums/{userId}/{groupId}/{albumId}";

  public function __construct() {
    parent::__construct('album_service');
  }

  /**
   * Deletes the album. The URI structure: /{userId}/{groupId}/{albumId}
   */
  public function handleDelete(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ALBUM_PATH);

    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $albumIds = $requestItem->getListParameter('albumId');

    HandlerPreconditions::requireSingular($userIds, "userId must be singular value.");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireSingular($albumIds, "albumId must singular value.");

    $this->service->deleteAlbum($userIds[0], $groupId, $albumIds[0], $requestItem->getToken());
  }

  /**
   * Gets the albums. The URI structure: /{userId}/{groupId}/{albumId}+.
   */
  public function handleGet(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ALBUM_PATH);

    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();

    HandlerPreconditions::requireSingular($userIds, "userId must be singular value.");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");

    $options = new CollectionOptions($requestItem);
    $fields = $requestItem->getFields();

    $albumIds = $requestItem->getListParameter('albumId');

    return $this->service->getAlbums($userIds[0], $groupId, $albumIds, $options, $fields, $requestItem->getToken());
  }

  /**
   * Creates an album. The URI structure: /{userId}/{groupId}.
   */
  public function handlePost(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ALBUM_PATH);

    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $album = $requestItem->getParameter('album');

    HandlerPreconditions::requireSingular($userIds, "userId must be of singular value");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireNotEmpty($album, "album must be specified.");

    return $this->service->createAlbum($userIds[0], $groupId, $album, $requestItem->getToken());
  }

  /**
   * Updates the album. The URI structure: /{userId}/{groupId}/{albumId}
   */
  public function handlePut(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$ALBUM_PATH);
    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $albumIds = $requestItem->getListParameter('albumId');
    $album = $requestItem->getParameter('album');

    HandlerPreconditions::requireSingular($userIds, "userId must be singular value");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireSingular($albumIds, "albumId must be singular value.");
    HandlerPreconditions::requireNotEmpty($album, "album must be specified.");

    $album['id'] = $albumIds[0];

    return $this->service->updateAlbum($userIds[0], $groupId, $album, $requestItem->getToken());
  }
}
