<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class opMobileSessionStorage extends sfSessionStorage
{
  public function initialize($options = null)
  {
    if (!sfContext::getInstance()->getRequest()->isCookie())
    {
      ini_set('use_only_cookies', 0);
      ini_set('use_cookies', 0);
      ini_set('session.use_trans_sid', 1);
    }

    parent::initialize($options);
  }
}
