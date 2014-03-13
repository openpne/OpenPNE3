/*
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

opensocial.DataRequest.prototype.newUpdatePersonAppDataRequest_09 =
    opensocial.DataRequest.prototype.newUpdatePersonAppDataRequest;
/**
 * Implementation of 0.8 newUpdatePersonAppDataRequest API
 */
opensocial.DataRequest.prototype.newUpdatePersonAppDataRequest = function(id,
    key, value) {
  if (id !== opensocial.IdSpec.PersonId.VIEWER) {
    throw Error("Cannot update app data for person "  + id);
  }
  return this.newUpdatePersonAppDataRequest_09(key, value);
};

opensocial.DataRequest.prototype.newRemovePersonAppDataRequest_09 =
    opensocial.DataRequest.prototype.newRemovePersonAppDataRequest;
/**
 * Implementation of 0.8 newRemovePersonAppDataRequest API
 */
opensocial.DataRequest.prototype.newRemovePersonAppDataRequest = function(id,
    keys) {
  if (id !== opensocial.IdSpec.PersonId.VIEWER) {
    throw Error("Cannot remove app data for person "  + id);
  }

  return this.newRemovePersonAppDataRequest_09(keys);
};
    
