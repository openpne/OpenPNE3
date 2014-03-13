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
 * Basic methods for InputAtomConverter and InputXmlConverter.
 */
class InputBasicXmlConverter {

  public static function loadString($requestParam, $namespace = null) {
    $entityLoaderConfig = libxml_disable_entity_loader(true);
    $result = simplexml_load_string($requestParam, 'SimpleXMLElement', LIBXML_NOCDATA, $namespace);
    libxml_disable_entity_loader($entityLoaderConfig);

    return $result;
  }

  public static function convertActivities($xml, $activityXml) {
    $activity = array();
    if (! isset($xml->title)) {
      throw new Exception("Mallformed activity xml");
    }
    // remember to either type cast to (string) or trim() the string so we don't get 
    // SimpleXMLString types in the internal data representation. I often prefer
    // using trim() since it cleans up the data too
    $activity['id'] = isset($xml->id) ? trim($xml->id) : '';
    $activity['title'] = trim($xml->title);
    $activity['body'] = isset($xml->summary) ? trim($xml->summary) : '';
    $activity['streamTitle'] = isset($activityXml->streamTitle) ? trim($activityXml->streamTitle) : '';
    $activity['streamId'] = isset($activityXml->streamId) ? trim($activityXml->streamId) : '';
    $activity['updated'] = isset($xml->updated) ? trim($xml->updated) : '';
    if (isset($activityXml->mediaItems)) {
      $activity['mediaItems'] = array();
      foreach ($activityXml->mediaItems->MediaItem as $mediaItem) {
        $item = array();
        if (! isset($mediaItem->type) || ! isset($mediaItem->mimeType) || ! isset($mediaItem->url)) {
          throw new Exception("Invalid media item in activity xml");
        }
        $item['type'] = trim($mediaItem->type);
        $item['mimeType'] = trim($mediaItem->mimeType);
        $item['url'] = trim($mediaItem->url);
        $activity['mediaItems'][] = $item;
      }
    }
    return $activity;
  }
  
  public static function convertAlbums($xml, $albumXml) {
    $fields = array('id', 'description', 'mediaItemCount', 'thumbnailUrl', 'ownerId', 'mediaMimeType');
    $album = self::copyFields($albumXml, $fields);
    if (isset($xml->title) && !empty($xml->title)) {
      $album['title'] = trim($xml->title);
    } else if (isset($albumXml->caption)) {
      $album['title'] = trim($albumXml->caption); 
    }
    if (isset($albumXml->mediaType) && in_array(strtoupper(trim($albumXml->mediaType)), MediaItem::$TYPES)) {
      $album['mediaType'] = strtoupper(trim($albumXml->mediaType));
    }
    if (isset($albumXml->location)) {
      $address = self::convertAddresses($albumXml->location);      
      if ($address) {
        $album['location'] = $address;
      }
    }
    return $album;
  }
  
  public static function convertMediaItems($xml, $mediaItemXml) {
    $fields = array('albumId', 'created', 'description', 'duration', 'fileSize', 'id', 'language',
      'lastUpdated', 'mimeType', 'numComments', 'numViews', 'numVotes', 'rating',
      'startTime', 'taggedPeople', 'tags', 'thumbnailUrl', 'url');
    $mediaItem = self::copyFields($mediaItemXml, $fields);
    if (isset($xml->title) && !empty($xml->title)) {
      $mediaItem['title'] = trim($xml->title);
    } else if (isset($mediaItemXml->caption)) {
      $mediaItem['title'] = trim($mediaItemXml->caption);
    }
    if (isset($mediaItemXml->type) && in_array(strtoupper(trim($mediaItemXml->type)), MediaItem::$TYPES)) {
      $mediaItem['type'] = strtoupper(trim($mediaItemXml->type));
    }
    if (isset($mediaItemXml->location)) {
      $address = self::convertAddresses($mediaItemXml->location);      
      if ($address) {
        $mediaItem['location'] = $address;
      }
    }
    return $mediaItem;
  }
  
  public static function convertAddresses($xml) {
    $fields = array('country', 'extendedAddress', 'latitude', 'locality', 'longitude', 'poBox',
      'postalCode', 'region', 'streetAddress', 'type', 'unstructuredAddress', 'formatted');
    return self::copyFields($xml, $fields);
  }
  
  public static function copyFields($xml, $fields) {
    $object = array();
    if (!is_array($fields)) {
      $fields = array($fields);
    }
    foreach ($fields as $field) {
      if ($xml && isset($xml->$field)) {
        $object[$field] = trim($xml->$field);
      }
    }
    return $object;
  }
  
  public static function convertMessages($requestParam, $xml, $content) {
    // As only message handler has the context to know whether it's a message or a message
    // collection request. All the fields for both the Message and the MessageCollection
    // classes are converted here. Message handler has the responsibility to validate the
    // params.
    $message = array();
    if (isset($xml->id)) {
      $message['id'] = trim($xml->id);
    }
    if (isset($xml->title)) {
      $message['title'] = trim($xml->title);
    }
    if (!empty($content)) {
      $message['body'] = trim($content);
    }
    if (isset($xml->bodyId)) {
      $meesage['bodyId'] = trim($xml->bodyId);
    }
    if (isset($xml->titleId)) {
      $message['titleId'] = trim($xml->titleId);
    }
    if (isset($xml->appUrl)) {
      $message['appUrl'] = trim($xml->appUrl);
    }
    if (isset($xml->status)) {
      $message['status'] = trim($xml->status);
    }
    if (isset($xml->timeSent)) {
      $message['timeSent'] = trim($xml->timeSent);
    }
    if (isset($xml->type)) {
      $message['type'] = trim($xml->type);
    }
    if (isset($xml->updated)) {
      $message['updated'] = trim($xml->updated);
    }
    if (isset($xml->senderId)) {
      $message['senderId'] = trim($xml->senderId);
    }
    if (isset($xml->appUrl)) {
      $message['appUrl'] = trim($xml->appUrl);
    }
    if (isset($xml->collectionIds)) {
      $message['collectionIds'] = array();
      foreach ($xml->collectionIds as $collectionId) {
        $message['collectionIds'][] = trim($collectionId);
      }
    }
    
    // Tries to retrieve recipients by looking at the osapi name space first then
    // the default namespace.
    $recipientXml = self::loadString($requestParam, "http://opensocial.org/2008/opensocialapi");
    if (empty($recipientXml) || !isset($recipientXml->recipient)) {
      $recipientXml = $xml;
    }
    
    if (isset($recipientXml->recipient)) {
      $message['recipients'] = array();
      foreach ($recipientXml->recipient as $recipient) {
        $message['recipients'][] = trim($recipient);
      }
    }
    
    // TODO: Parses the inReplyTo, replies and urls fields.
    
    // MessageCollection specified fiedls.
    if (isset($xml->total)) {
      $message['total'] = trim($xml->total);
    }
    if (isset($xml->unread)) {
      $message['unread'] = trim($xml->unread);
    }
    
    return $message;
  }
}
