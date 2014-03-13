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
 * This class represents a RESTful social data response
 */
class RestfulCollection {
  
  public $entry;
  public $startIndex;
  public $totalResults;
  public $itemsPerPage;
  
  // boolean flags to indicate whether the requested operations were performed or declined
  public $filtered;
  public $sorted;
  public $updatedSince;

  public static function createFromEntry($entry) {
    return new RestfulCollection($entry, 0, count($entry));
  }

  public function __construct($entry, $startIndex, $totalResults) {
    $this->entry = $entry;
    $this->startIndex = $startIndex;
    $this->totalResults = $totalResults;
  }

  public function getEntry() {
    return $this->entry;
  }

  public function setEntry($entry) {
    $this->entry = $entry;
  }

  public function getStartIndex() {
    return $this->startIndex;
  }

  public function setStartIndex($startIndex) {
    $this->startIndex = $startIndex;
  }

  public function getItemsPerPage() {
    return $this->itemsPerPage;
  }

  public function setItemsPerPage($itemsPerPage) {
    $this->itemsPerPage = $itemsPerPage;
  }

  public function getTotalResults() {
    return $this->totalResults;
  }

  public function setTotalResults($totalResults) {
    $this->totalResults = $totalResults;
  }

  public function getFiltered($filtered) {
    $this->filtered = $filtered;
  }

  public function setFiltered($filtered) {
    $this->filtered = $filtered;
  }

  public function getSorted($sorted) {
    $this->sorted = $sorted;
  }

  public function setSorted($sorted) {
    $this->sorted = $sorted;
  }

  public function getUpdatedSince($updatedSince) {
    $this->updatedSince = $updatedSince;
  }

  public function setUpdatedSince($updatedSince) {
    $this->updatedSince = $updatedSince;
  }
}
