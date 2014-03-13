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

interface PersonService {

  /**
   * Returns a Person object for person with $id or false on not found
   *
   * @param container specific id $id
   * @param fields set of contact fields to return, as array('fieldName' => 1)
   * @param security token $token
   */
  function getPerson($userId, $groupId, $fields, SecurityToken $token);

  /**
   * Returns a list of people that correspond to the passed in person ids.
   * @param ids The ids of the people to fetch.
   * @param options Request options for filtering/sorting/paging
   * @param fields set of contact fields to return, as array('fieldName' => 1)
   * @return a list of people.
   */
  function getPeople($userId, $groupId, CollectionOptions $options, $fields, SecurityToken $token);
}

