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
 * Error strings to be returned to gadgets as "oauthError" data.
 */
class OAuthError {
  /**
   * The request cannot be completed because the OAuth configuration for
   * the gadget is incorrect.
   */
  public static $BAD_OAUTH_CONFIGURATION = "BAD_OAUTH_CONFIGURATION";
  
  /**
   * The request cannot be completed for an unspecified reason.
   */
  public static $UNKNOWN_PROBLEM = "UNKNOWN_PROBLEM";
}
