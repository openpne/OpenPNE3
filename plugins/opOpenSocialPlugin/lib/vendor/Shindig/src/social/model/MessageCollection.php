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
 * http://opensocial-resources.googlecode.com/svn/spec/draft/OpenSocial-Specification.xml#opensocial.MessageCollection.Field
 *
 */
class MessageCollection {
  // Indicates the collection of all messages sent to the user
  public static $INBOX = '@inbox';
  // Indicates the collection of all messages sent by the user
  // and used as a special endpoint for posting outbound messages.
  public static $OUTBOX = '@outbox';
  // All the messages both sent from and to the user.
  public static $ALL = '@all';

  // These fileds should be referenced via getters and setters. 'public' only for json_encode. 
  public $id;
  public $title;
  public $total;
  public $unread;
  public $updated;
  public $urls = array();

  public static $DEFAULT_FIELDS = array('id', 'title', 'total', 'unread', 'updated', 'urls');

  public function __construct($id, $title) {
    $this->setId($id);
    $this->setTitle($title);
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

  public function getTotal() {
    return $this->total;
  }

  public function setTotal($total) {
    $this->total = $total;
  }

  public function getUnread() {
    return $this->unread;
  }

  public function setUnread($unread) {
    $this->unread = $unread;
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setUpdated($updated) {
    $this->updated = $updated;
  }

  public function getUrls() {
    return $this->urls;
  }

  public function setUrls($urls) {
    $this->urls = $urls;
  }
}

