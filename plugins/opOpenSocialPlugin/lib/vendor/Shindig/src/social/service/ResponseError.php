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
 * An Enumeration for holding all the responses emitted by the social API.
 */
class ResponseError {
  /** value representing NOT IMPLEMENTED. */
  public static $NOT_IMPLEMENTED = 501;
  /** value representing UNAUTHORIZED. */
  public static $UNAUTHORIZED = 401;
  /** value representing FORBIDDEN. */
  public static $FORBIDDEN = 403;
  /** value representing BAD REQUEST. */
  public static $BAD_REQUEST = 400;
  /** value representing NOT FOUND. */
  public static $NOT_FOUND = 404;
  /** value representing INTERNAL SERVER ERROR. */
  public static $INTERNAL_ERROR = 500;
  /** value representing EXPECTATION FAILED. */
  public static $LIMIT_EXCEEDED = 409;
  
  /**
   * The json value of the error.
   */
  private $jsonValue;
  /**
   * The http error code associated with the error.
   */
  private $httpErrorCode;
  
  /**
   * The HTTP response header
   */
  private $httpErrorMsg;

  /**
   * Construct a Response Error from the jsonValue as a string and the Http Error Code.
   * @param jsonValue the json String representation of the error code.
   * @param httpErrorCode the numeric HTTP error code.
   */
  public function __construct($jsonValue) {
    $this->jsonValue = $jsonValue;
    switch ($this->jsonValue) {
      case self::$BAD_REQUEST:
        $this->httpErrorMsg = '400 Bad Request';
        $this->httpErrorcode = 400;
        break;
      case self::$UNAUTHORIZED:
        $this->httpErrorMsg = '401 Unauthorized';
        $this->httpErrorcode = 401;
        break;
      case self::$FORBIDDEN:
        $this->httpErrorMsg = '403 Forbidden';
        $this->httpErrorcode = 403;
        break;
      case self::$NOT_FOUND:
        $this->httpErrorMsg = '404 Not Found';
        $this->httpErrorcode = 404;
        break;
      case self::$NOT_IMPLEMENTED:
        $this->httpErrorMsg = '501 Not Implemented';
        $this->httpErrorcode = 501;
        break;
      case self::$LIMIT_EXCEEDED:
        //FIXME or should this be a 507 Insufficient Storage (WebDAV, RFC 4918) ?
        $this->httpErrorMsg = '509 Limit Exceeeded';
        $this->httpErrorcode = 509;
        break;
      case self::$INTERNAL_ERROR:
      default:
        $this->httpErrorMsg = '500 Internal Server Error';
        $this->httpErrorcode = 500;
        break;
    }
  }

  /**
   *
   * Converts the ResponseError to a String representation
   */
  public function toString() {
    return $this->jsonValue;
  }

  /**
   * Get the HTTP error code.
   * @return the Http Error code.
   */
  public function getHttpErrorCode() {
    return $this->httpErrorCode;
  }

  /**
   * Get the HTTP error response header.
   * @return the Http response header.
   */
  public function getHttpErrorMsg() {
    return $this->httpErrorMsg;
  }
}
