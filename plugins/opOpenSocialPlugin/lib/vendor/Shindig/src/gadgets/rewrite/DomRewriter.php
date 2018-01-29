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
 * Abstract base class for DOM based rewriters. The GadgetRewriter will call the
 *
 *
 */
abstract class DomRewriter {
  protected $context;
  protected $gadget;

  public function __construct(GadgetContext $context, Shindig_Gadget &$gadget) {
    $this->context = $context;
    $this->gadget = $gadget;
  }

  /**
   * Function to register the element => function mappings with the GadgetRewriter.
   * Always use lower case tag names when calling GadgetRewriter->observer
   *
   * @param GadgetRewriter $gadgetRewriter
   */
  abstract public function register(GadgetRewriter &$gadgetRewriter);
}