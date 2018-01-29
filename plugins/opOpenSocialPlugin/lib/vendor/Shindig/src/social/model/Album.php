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
 * http://opensocial-resources.googlecode.com/svn/spec/0.9/OpenSocial-Specification.xml#opensocial.Album
 */
class Shindig_Album {

  public $id;
  public $title;
  public $description;
  public $location;
  public $mediaItemCount;
  public $ownerId;
  public $thumbnailUrl;
  public $mediaMimeType;
  public $mediaType;
  
  public function __construct($id, $ownerId) {
    $this->setId($id);
    $this->setOwnerId($ownerId);
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function setId($id) {
    $this->id = $id;
  }
  
  public function getTitle() {
    return $this->title;
  }
  
  public function setTitle($title) {
    $this->title = $title;
  }
  
  public function getDescription() {
    return $this->description;
  }
  
  public function setDescription($description) {
    $this->description = $description;
  }
  
  public function getLocation() {
    return $this->location;
  }
  
  public function setLocation($location) {
    $this->location = $location;
  }
  
  public function getMediaItemCount() {
    return $this->mediaItemCount;
  }
  
  public function setMediaItemCount($mediaItemCount) {
    $this->mediaItemCount = $mediaItemCount > 0 ? $mediaItemCount : 0;
  }
  
  public function getOwnerId() {
    return $this->ownerId;
  }
  
  public function setOwnerId($ownerId) {
    $this->ownerId = $ownerId;
  }
  
  public function getThumbnailUrl() {
    return $this->thumbnailUrl;
  }
  
  public function setThumbnailUrl($thumbnailUrl) {
    $this->thumbnailUrl = $thumbnailUrl;
  }
  
  public function getMediaMimeType() {
    return $this->mediaMimeType;
  }
  
  public function setMediaMimeType($mediaMimeType) {
    $this->mediaMimeType = $mediaMimeType;
  }
  
  public function getMediaType() {
    return $this->mediaType;
  }
  
  public function setMediaType($mediaType) {
    if (!in_array($mediaType, MediaItem::$TYPES)) {
      throw new Exception("Invalid Media type");
    }
    $this->mediaType = $mediaType;
  }
}
