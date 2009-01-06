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

/**
 * opWidgetFormInputHiddenNextUri represents a hidden HTML input tag for next_uri
 *
 * @package    OpenPNE
 * @subpackage widget
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opWidgetFormInputHiddenNextUri extends sfWidgetFormInputHidden
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $routing = sfContext::getInstance()->getRouting();
    $request = sfContext::getInstance()->getRequest();

    // FIXME
    $params = http_build_query($request->getGetParameters());
    $value = $routing->getCurrentInternalUri();
    if ($params)
    {
      $value .= '?'.str_replace('openid_', 'openid.', $params);
    }

    $this->setAttribute('value', $value);
  }
}
