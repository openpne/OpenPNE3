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
 * http://opensocial-resources.googlecode.com/svn/spec/draft/OpenSocial-Specification.xml#opensocial.Message.Field
 */
class Shindig_Message {
  // These fields should be referenced via getters and setters. 'public' only for json_encode. 
  public $appUrl;
  public $body;
  public $bodyId;
  public $collectionIds;
  public $id;
  public $inReplyTo;
  public $recipients;
  public $replies;
  public $senderId;
  public $status;
  public $timeSent;
  public $title;
  public $titleId;
  public $type;
  public $updated;
  public $urls;
  
  public static $DEFAULT_FIELDS = array('appUrl', 'body', 'bodyId',
      'collectionIds', 'id', 'inReplyTo', 'recipients', 'replies',
      'senderId', 'status', 'timeSent', 'title', 'titleId', 'type',
      'updated', 'urls');
  
  public static $TYPES = array(
      /* An email */
      'EMAIL',
      /* A short private message */
      'NOTIFICATION',
      /* A message to a specific user that can be seen only by that user */
      'PRIVATE_MESSAGE',
      /* A message to a specific user that can be seen by more than that user */
      'PUBLIC_MESSAGE');
  
  public static $STATUS = array('NEW', 'READ', 'DELETED');

  public function __construct($id, $title) {
    $this->setId($id);
    $this->setTitle($title);
  }

  public function getAppUrl() {
    return $this->appUrl;
  }
  
  public function setAppUrl($url) {
    $this->url = $url;
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

  public function getCollectionIds() {
    return $this->collectionIds;
  }

  public function setCollectionIds($collectionIds) {
    $this->$collectionIds = $collectionIds;
  }

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  public function getInReplyTo() {
    return $this->inReplyTo;
  }

  public function setInReplyTo($inReplyTo) {
    $this->inReplyTo = $inReplyTo;
  }

  public function getRecipients() {
    return $this->recipients;
  }

  public function setRecipients($recipients) {
    $this->recipients = $recipients;
  }
  
  public function getReplies() {
    return $this->replies;
  }
  
  public function setReplies($replies) {
    $this->replies = $replies;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($status) {
    $this->status = $status;
  }

  public function getSenderId() {
    return $this->senderId;
  }
  
  public function setSenderId($senderId) {
    $this->senderId = $senderId;
  }

  public function getTimeSent() {
    return $this->timeSent;
  }

  public function setTimeSent($timeSent) {
    $this->timeSent = $timeSent;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getTitleId() {
    return $this->titleId;
  }

  public function setTitleId($titleId) {
    $this->titleId = $titleId;
  }

  public function getType() {
    return $this->type;
  }

  public function setType($type) {
    $this->type = $type;
  }
  
  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated($updated) {
    $this->updated = $updated;
  }

  /**
   * Gets the URLs related to the message
   * @return the URLs related to the person, their webpages, or feeds
   */
  public function getUrls() {
    return $this->urls;
  }

  /**
   * Sets the URLs related to the message
   * @param urls the URLs related to the person, their webpages, or feeds
   */
  public function setUrls($urls) {
    $this->urls = $urls;
  }
  
  /**
   * TODO implement either a standard 'sanitizing' facility or
   * define an interface that can be set on this class so
   * others can plug in their own.
   * @param htmlStr String to be sanitized.
   * @return the sanitized HTML String
   */
  public function sanitizeHTML($htmlStr) {
    return $htmlStr;
  }
}
