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
 * http://opensocial-resources.googlecode.com/svn/spec/0.9/OpenSocial-Specification.xml#opensocial.MediaItem
 */
class MediaItem {
  
  public $albumId;
  public $created;
  public $description;
  public $duration;
  public $fileSize;
  public $id;
  public $language;
  public $lastUpdated;
  public $location;
  public $mimeType;
  public $numComments;
  public $numViews;
  public $numVotes;
  public $rating;
  public $startTime;
  public $taggedPeople;
  public $tags;
  public $thumbnailUrl;
  public $title;
  public $type;
  public $url;
  
  public static $TYPES = array('AUDIO', 'VIDEO', 'IMAGE');

  public function __construct($mimeType, $type, $url) {
    $this->setMimeType($mimeType);
    $this->setType($type);
    $this->setUrl($url);
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }
  
  public function getAlbumId() {
    return $this->albumId;
  }

  public function setAlbumId($albumId) {
    $this->albumId = $albumId;
  }
  
  public function getCreated() {
    return $this->created;
  }

  public function setCreated($created) {
    $this->created = $created;
  }

  public function getDescription() {
    return $this->$description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }
  
  public function getDuration() {
    return $this->duration;
  }

  public function setDuration($duration) {
    $this->duration = $duration;
  }
  
  public function getFileSize() {
    return $this->fileSize;
  }

  public function setFileSize($fileSize) {
    $this->fileSize = $fileSize;
  }
  
  public function getLanguage() {
    return $this->language;
  }

  public function setLanguage($language) {
    $this->language = $language;
  }
  
  public function getLastUpdated() {
    return $this->lastUpdated;
  }

  public function setLastUpdated($lastUpdated) {
    $this->lastUpdated = $lastUpdated;
  }
  
  public function getLocation() {
    return $this->location;
  }

  public function setLocation($location) {
    $this->location = $location;
  }
  
  public function getNumComments() {
    return $this->numComments;
  }

  public function setNumComments($numComments) {
    $this->numComments = $numComments;
  }
  
  public function getNumViews() {
    return $this->numViews;
  }

  public function setNumViews($numViews) {
    $this->numViews = $numViews;
  }
  
  public function getNumVotes() {
    return $this->numVotes;
  }

  public function setNumVotes($numVotes) {
    $this->numVotes = $numVotes;
  }
  
  public function getRating() {
    return $this->rating;
  }

  public function setRating($rating) {
    $this->rating = $rating;
  }
  
  public function getStartTime() {
    return $this->startTime;
  }

  public function setStartTime($startTime) {
    $this->startTime = $startTime;
  }
  
  public function getTaggedPeople() {
    return $this->taggedPeople;
  }

  public function setTaggedPeople($taggedPeople) {
    $this->taggedPeople = $taggedPeople;
  }
  
  public function getTags() {
    return $this->tags;
  }

  public function setTags($tags) {
    $this->tags = $tags;
  }
  
  public function getThumbnailUrl() {
    return $this->thumbnailUrl;
  }

  public function setThumbnailUrl($thumbnailUrl) {
    $this->thumbnailUrl = $thumbnailUrl;
  }
  
  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }
  
  public function getMimeType() {
    return $this->mimeType;
  }

  public function setMimeType($mimeType) {
    $this->mimeType = $mimeType;
  }

  public function getType() {
    return $this->type;
  }

  public function setType($type) {
    if (! in_array($type, self::$TYPES)) {
      throw new Exception("Invalid Media type");
    }
    $this->type = $type;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl($url) {
    $this->url = $url;
  }
}
