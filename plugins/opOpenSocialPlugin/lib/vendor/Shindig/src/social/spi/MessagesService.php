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

interface MessagesService {

  /**
   * Creates a new message collection for the given arguments.
   * NOTE: It'd better not to use 0 or "0" as message collection id
   * to prevent pertential problems.
   * @param userId  The userId to create the message collection for
   * @param msgCollection A message collection that is to be created
   * @param token  A security token for this request
   */
  public function createMessageCollection($userId, $msgCollection, $token);

  /**
   * Updates a message collection for the given arguments
   * @param userId  The userId to update the message collection for
   * @param msgCollection Data for the message collection to be updated
   * @param token  A security token for this request
   */
  public function updateMessageCollection($userId, $msgCollection, $token);

  /**
   * Deletes a message collection for the given arguments
   * @param userId  The userId to create the message collection for
   * @param msgCollId The message collection id to be deleted
   * @param token  A security token for this request
   */
  public function deleteMessageCollection($userId, $msgCollId, $token);

  /**
   * Returns a list of message collections corresponding to the given user
   * @param userId   The User to fetch for
   * @param fields   The fields that the returned message collections contain.
   * @param options  Filter criteria, pagination, etc.
   * @param token    Given security token for this request
   * @return a collection of message collections.
   */
  public function getMessageCollections($userId, $fields, $options, $token);

  /**
   * Posts a message to the user's specified message collection and sends the
   * message to the set of recipients specified in the message.
   * @param userId      The user posting the message.
   * @param msgCollId   The message collection Id to post to
   * @param message     The message to post
   * @param token       A valid security token
   */
  public function createMessage($userId, $msgCollId, $message, $token);

  /**
   * Updates a specific message with new data
   * @param userId      The User to modify for
   * @param msgCollId   The Message Collection ID to update from
   * @param message     The message details to modify
   * @param token       Given Security Token for this request
   */
  public function updateMessage($userId, $msgCollId, $message, $token);

  /**
   * Deletes a set of messages for a given user/message collection
   * @param userId      The User to delete for
   * @param msgCollId   The Message Collection ID to delete from
   * @param messageIds  List of IDs to delete
   * @param token       Given Security Token for this request
   */
  public function deleteMessages($userId, $msgCollId, $messageIds, $token);

  /**
   * Returns a collection of messages that correspond to the passed in criteria.
   * The container implementation can get appId from the token and translate
   * the appId to appUrl in the response.
   * @param userId     The user id to fetch for
   * @param msgCollId  A message collection id. Supports @inbox and @outbox defined in MessageCollection class.
   * @param fields     The fields to fetch for the messages
   * @param msgIds     An explicit set of message ids to fetch. Empty means all the messages that fulfills the given criterias.
   * @param options    Options to control the fetch.
   * @param token      Given security token for this request
   * @return a collection of messages
   */
  public function getMessages($userId, $msgCollId, $fields, $msgIds, $options, $token);

}
