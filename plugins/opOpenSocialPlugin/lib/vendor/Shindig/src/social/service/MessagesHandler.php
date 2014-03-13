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

class MessagesHandler extends DataRequestHandler {

  private static $MESSAGES_PATH = "/messages/{userId}/msgCollId/{messageId}";

  public function __construct() {
    parent::__construct('messages_service');
  }

  /**
   * Deletes the message collection or the messages.
   */
  public function handleDelete(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MESSAGES_PATH);

    $userIds = $requestItem->getUsers();
    HandlerPreconditions::requireSingular($userIds, "UserId can only be singular.");
    $msgCollId = $requestItem->getParameter("msgCollId");
    HandlerPreconditions::requireNotEmpty($msgCollId, "A message collection id is required");

    $token = $requestItem->getToken();
    $messageIds = $requestItem->getListParameter("messageId");
    if (empty($messageIds)) {
      $this->service->deleteMessageCollection($userIds[0], $msgCollId, $token);
    } else {
      $this->service->deleteMessages($userIds[0], $msgCollId, $messageIds, $token);
    }
  }

  /**
   * Returns a list of message collections or messages.
   * Examples:
   * /messages/john.doe
   * /messages/john.doe/notification
   * /messages/john.doe/notification/1,2,3
   */
  public function handleGet(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MESSAGES_PATH);

    $userIds = $requestItem->getUsers();
    HandlerPreconditions::requireSingular($userIds, "UserId is not singular.");

    $options = new CollectionOptions($requestItem);
    $msgCollId = $requestItem->getParameter("msgCollId");

    $token = $requestItem->getToken();
    if (empty($msgCollId)) {
      // Gets the message collections.
      return $this->service->getMessageCollections($userIds[0], $requestItem->getFields(MessageCollection::$DEFAULT_FIELDS), $options, $token);
    }

    $messageIds = $requestItem->getListParameter("messageId");
    if (empty($messageIds)) {
      $messageIds = array();
    }
    return $this->service->getMessages($userIds[0], $msgCollId, $requestItem->getFields(Message::$DEFAULT_FIELDS), $messageIds, $options, $token);
  }

  /**
   * Creates a new message collection or message.
   * Exapmples:
   * /messages/john.doe
   * /messages/john.doe/notification
   */
  public function handlePost(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MESSAGES_PATH);

    $userIds = $requestItem->getUsers();
    HandlerPreconditions::requireSingular($userIds, "UserId is not singular.");

    $msgCollId = $requestItem->getParameter("msgCollId");
    $entity = $requestItem->getParameter("entity");
    
    // If the parameters contain 'message' the request is from the old api(< 0.9).
    if ($requestItem->getParameter("message")) {
      $entity = $requestItem->getParameter("message");
      $msgCollId = "@outbox";
    }

    if (empty($msgCollId)) {
      // Creates a message collection.
      $messageCollection = $entity;
      HandlerPreconditions::requireNotEmpty($messageCollection, "Filed entity not specified.");
      $title = isset($messageCollection['title']) ? trim($messageCollection['title']) : '';
      HandlerPreconditions::requireCondition(strlen($title) > 0, "Title must be specified.");
      return $this->service->createMessageCollection($userIds[0], $messageCollection, $requestItem->getToken());
    } else {
      // Creates a message.
      $messageIds = $requestItem->getListParameter("messageId");
      HandlerPreconditions::requireEmpty($messageIds, "messageId cannot be specified in create method.");
      $message = $entity;
      HandlerPreconditions::requireNotEmpty($message, "Filed entity not specified.");
      HandlerPreconditions::requireEmpty($messageIds, "messageId cannot be specified in create method.");

      // Message fields validation.
      $title = isset($message['title']) ? trim($message['title']) : '';
      HandlerPreconditions::requireCondition(strlen($title) > 0, "Title must be specified.");
      HandlerPreconditions::requireNotEmpty($message['recipients'], "Field recipients is required.");
      HandlerPreconditions::requireCondition(is_array($message['recipients']), "recipients must be array.");

      return $this->service->createMessage($userIds[0], $msgCollId, $message, $requestItem->getToken());
    }
  }

  /**
   * Updates a message or a message collection.
   */
  public function handlePut(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$MESSAGES_PATH);

    $userIds = $requestItem->getUsers();
    HandlerPreconditions::requireSingular($userIds, "UserId is not singular.");

    $msgCollId = $requestItem->getParameter("msgCollId");
    HandlerPreconditions::requireNotEmpty($msgCollId, "msgCollId is required.");

    $messageIds = $requestItem->getListParameter("messageId");
    if (empty($messageIds)) {
      // Updates message collection.
      $messageCollection = $requestItem->getParameter("entity");
      $messageCollection['id'] = $msgCollId;
      HandlerPreconditions::requireNotEmpty($messageCollection, "Can't parse message collection.");
      return $this->service->updateMessageCollection($userIds[0], $messageCollection, $requestItem->getToken());
    } else {
      // Updates a message.
      HandlerPreconditions::requireSingular($messageIds, "Message id is not singular.");
      $message = $requestItem->getParameter("entity");
      $message['id'] = $messageIds[0];
      HandlerPreconditions::requireNotEmpty($message, "Can't parse message.");
      return $this->service->updateMessage($userIds[0], $msgCollId, $message, $requestItem->getToken());
    }
  }
}
