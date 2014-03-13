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
 * see
 * http://www.opensocial.org/Technical-Resources/opensocial-spec-v081/opensocial-reference#opensocial.Activity
 */
class Activity {
  public $appId;
  public $body;
  public $bodyId;
  public $externalId;
  public $id;
  public $mediaItems;
  public $postedTime;
  public $priority;
  public $streamFaviconUrl;
  public $streamSourceUrl;
  public $streamTitle;
  public $streamUrl;
  public $templateParams;
  public $title;
  public $titleId;
  public $url;
  public $userId;

  public function __construct($id, $userId) {
    $this->id = $id;
    $this->userId = $userId;
  }

  public function getAppId() {
    return $this->appId;
  }

  public function setAppId($appId) {
    $this->appId = $appId;
  }

  public function getBody() {
    return $this->body;
  }

  public function setBody($body) {
    $this->body = $body;
  }

  public function getBodyId() {
    return $this->bodyId;
  }

  public function setBodyId($bodyId) {
    $this->bodyId = $bodyId;
  }

  public function getExternalId() {
    return $this->externalId;
  }

  public function setExternalId($externalId) {
    $this->externalId = $externalId;
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getMediaItems() {
    return $this->mediaItems;
  }

  public function setMediaItems($mediaItems) {
    $this->mediaItems = $mediaItems;
  }

  public function getPostedTime() {
    return $this->postedTime;
  }

  public function setPostedTime($postedTime) {
    $this->postedTime = $postedTime;
  }

  public function getPriority() {
    return $this->priority;
  }

  public function setPriority($priority) {
    $this->priority = $priority;
  }

  public function getStreamFaviconUrl() {
    return $this->streamFaviconUrl;
  }

  public function setStreamFaviconUrl($streamFaviconUrl) {
    $this->streamFaviconUrl = $streamFaviconUrl;
  }

  public function getStreamSourceUrl() {
    return $this->streamSourceUrl;
  }

  public function setStreamSourceUrl($streamSourceUrl) {
    $this->streamSourceUrl = $streamSourceUrl;
  }

  public function getStreamTitle() {
    return $this->streamTitle;
  }

  public function setStreamTitle($streamTitle) {
    $this->streamTitle = $streamTitle;
  }

  public function getStreamUrl() {
    return $this->streamUrl;
  }

  public function setStreamUrl($streamUrl) {
    $this->streamUrl = $streamUrl;
  }

  public function getTemplateParams() {
    return $this->templateParams;
  }

  public function setTemplateParams($templateParams) {
    $this->templateParams = $templateParams;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = strip_tags($title, '<b><i><a><span><img>');
  }

  public function getTitleId() {
    return $this->titleId;
  }

  public function setTitleId($titleId) {
    $this->titleId = $titleId;
  }

  public function getUrl() {
    return $this->url;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

}
