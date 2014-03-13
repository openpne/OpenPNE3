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

class MediaItemHandler extends DataRequestHandler {
  private static $MEDIA_ITEM_PATH = "/mediaitems/{userId}/{groupId}/{albumId}/{mediaItemId}";
  
  public function __construct() {
    parent::__construct('media_item_service');
  }
  
  /**
   * Deletes the media items. The URI structure: /{userId}/{groupId}/{albumId}/{mediaItemId}+
   */
  public function handleDelete(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MEDIA_ITEM_PATH);
    
    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $albumIds = $requestItem->getListParameter('albumId');
    $mediaItemIds = $requestItem->getListParameter('mediaItemId');

    HandlerPreconditions::requireSingular($userIds, "userId must be singular value.");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireSingular($albumIds, "albumId must be singular value.");

    $this->service->deleteMediaItems($userIds[0], $groupId, $albumIds[0], $mediaItemIds, $requestItem->getToken());
  }

  /**
   * Gets the media items. The URI structure: /{userId}/{groupId}/{albumId}/{mediaItemId}+
   */
  public function handleGet(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MEDIA_ITEM_PATH);
    
    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $albumIds = $requestItem->getListParameter("albumId");
    $mediaItemIds = $requestItem->getListParameter("mediaItemId");
        
    HandlerPreconditions::requireSingular($userIds, "userId must be singular value.");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireSingular($albumIds, "albumId must be singular value.");

    $options = new CollectionOptions($requestItem);
    $fields = $requestItem->getFields();

    return $this->service->getMediaItems($userIds[0], $groupId, $albumIds[0], $mediaItemIds, $options, $fields, $requestItem->getToken());
  }

  /**
   * Creates the media item. The URI structure: /{userId}/{groupId}/{albumId}.
   */
  public function handlePost(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MEDIA_ITEM_PATH);
    
    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $albumIds = $requestItem->getListParameter('albumId');
    $mediaItem = $requestItem->getParameter('mediaItem');

    HandlerPreconditions::requireSingular($userIds, "userId must be of singular value");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireSingular($albumIds, "albumId must be sigular value.");
    HandlerPreconditions::requireNotEmpty($mediaItem, "mediaItem must be specified.");
    
    // The null param is the content data(image, video and audio binaries) uploaded by the user.
    return $this->service->createMediaItem($userIds[0], $groupId, $mediaItem, null, $requestItem->getToken());
  }

  /**
   * Updates the mediaItem. The URI structure: /{userId}/{groupId}/{albumId}/{mediaItemId}
   */
  public function handlePut(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MEDIA_ITEM_PATH);
    
    $userIds = $requestItem->getUsers();
    $groupId = $requestItem->getGroup();
    $albumIds = $requestItem->getListParameter('albumId');
    $mediaItemIds = $requestItem->getListParameter('mediaItemId');
    $mediaItem = $requestItem->getParameter('mediaItem');
    
    HandlerPreconditions::requireSingular($userIds, "userId must be singular value.");
    HandlerPreconditions::requireNotEmpty($groupId, "groupId must be specified.");
    HandlerPreconditions::requireSingular($albumIds, "albumId must be sigular value.");
    HandlerPreconditions::requireSingular($mediaItemIds, "mediaItemId must be sigular value.");
    HandlerPreconditions::requireNotEmpty($mediaItem, "mediaItem must be specified.");
    
    $mediaItem['id'] = $mediaItemIds[0];
    $mediaItem['albumId'] = $albumIds[0];
    // The null param is the content data(image, video and audio binaries) uploaded by the user.
    return $this->service->updateMediaItem($userIds[0], $groupId, $mediaItem, null, $requestItem->getToken());
  }
}
