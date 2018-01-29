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
 * Represents the request options for sorting/filtering/paging.
 */
class CollectionOptions {
  private $sortBy;
  private $sortOrder;
  const SORT_ORDER_ASCENDING = 'ascending';
  const SORT_ORDER_DESCENDING = 'descending';

  private $filterBy;
  private $filterOp;
  private $filterValue;

  const FILTER_OP_EQUALS = 'equals';
  const FILTER_OP_CONTAINS = 'contains';
  const FILTER_OP_STARTSWITH = 'startswith';
  const FILTER_OP_PRESENT = 'present';

  const TOP_FRIENDS_SORT = "topFriends";
  const TOP_FRIENDS_FILTER = "topFriends";
  const HAS_APP_FILTER = "hasApp";

  private $updatedSince;

  private $networkDistance;

  private $startIndex;
  private $count;

  public function __construct($requestItem = NULL) {
    if (empty($requestItem)) {
      $this->startIndex = 0;
      $this->count = 0;
      $this->sortOrder = CollectionOptions::SORT_ORDER_ASCENDING;
    } else {
      $this->setSortBy($requestItem->getSortBy());
      $this->setSortOrder($requestItem->getSortOrder());
      $this->setFilterBy($requestItem->getFilterBy());
      $this->setFilterOperation($requestItem->getFilterOperation());
      $this->setFilterValue($requestItem->getFilterValue());
      $this->setStartIndex($requestItem->getStartIndex());
      $this->setCount($requestItem->getCount());
    }
  }

  public function getSortBy() {
    return $this->sortBy;
  }

  public function setSortBy($sortBy) {
    $this->sortBy = $sortBy;
  }

  public function getSortOrder() {
    return $this->sortOrder;
  }

  public function setSortOrder($sortOrder) {
    $this->sortOrder = $sortOrder;
  }

  public function getFilterBy() {
    return $this->filterBy;
  }

  public function setFilterBy($filterBy) {
    $this->filterBy = $filterBy;
  }

  public function getFilterOperation() {
    return $this->filterOp;
  }

  public function setFilterOperation($filterOp) {
    $this->filterOp = $filterOp;
  }

  public function getFilterValue() {
    return $this->filterValue;
  }

  public function setFilterValue($filterValue) {
    $this->filterValue = $filterValue;
  }

  public function getUpdatedSince() {
    return $this->updatedSince;
  }

  public function setUpdatedSince($updatedSince) {
    $this->updatedSince = $updatedSince;
  }

  public function getNetworkDistance() {
    return $this->networkDistance;
  }

  public function setNetworkDistance($networkDistance) {
    $this->networkDistance = $networkDistance;
  }

  public function getStartIndex() {
    return $this->startIndex;
  }

  public function setStartIndex($startIndex) {
    $this->startIndex = $startIndex;
  }

  public function getCount() {
    return $this->count;
  }

  public function setCount($count) {
    $this->count = $count;
  }
}
