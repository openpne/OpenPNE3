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


/*
 * remoteContent* classes, we departed from the shindig java base style a bit here
 * We want to use curl_multi for our content fetching because we don't have any fancy
 * worker queue's where the java variant does.
 * So a different methodlogy which calls for a different working unfortunatly, however
 * it's kept in the spirit of the java variant as much as possible
 */

class RemoteContentException extends Exception {
}

abstract class RemoteContent {

  abstract public function fetch(RemoteContentRequest $request);

  abstract public function multiFetch(Array $requests);
  
  abstract public function invalidate(RemoteContentRequest $request);
}
