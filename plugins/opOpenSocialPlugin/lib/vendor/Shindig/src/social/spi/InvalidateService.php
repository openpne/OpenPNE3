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

interface InvalidateService {
  /**
   * Invalidate a set of cached resources that are part of the application specification itself.
   * This includes gadget specs, manifests and message bundles
   * @param uris of content to invalidate
   * @param token identifying the calling application
   */
  function invalidateApplicationResources(Array $uris, SecurityToken $token);

  /**
   * Invalidate all cached resources where the specified user ids were used as either the
   * owner or viewer id when a signed or OAuth request was made for the content by the application
   * identified in the security token.
   * @param opensocialIds Set of user ids to invalidate authenticated/signed content for
   * @param token identifying the calling application
   */
  function invalidateUserResources(Array $opensocialIds, SecurityToken $token);

  /**
   * Is the specified request still valid. If the request is signed or authenticated
   * has its content been invalidated by a call to invalidateUserResource subsequent to the
   * response being cached.
   */
  function isValid(RemoteContentRequest $request);

  /**
   * Mark the request prior to caching it so that subsequent calls to isValid can detect
   * if it has been invalidated.
   */
  function markResponse(RemoteContentRequest $request);
}

