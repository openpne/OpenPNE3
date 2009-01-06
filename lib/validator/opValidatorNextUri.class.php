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
 * opValidatorNextUri validates a next_uri.
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opValidatorNextUri extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setOption('required', false);
    $this->setOption('empty_value', '@homepage');
  }

  /**
   * @see sfValidatorString
   */
  protected function doClean($value)
  {
    $routing = sfContext::getInstance()->getRouting();
    $routeInfo = $routing->findRoute($value);

    if (sfConfig::get('sf_login_module') === $routeInfo['parameters']['module']
      && sfConfig::get('sf_login_action') === $routeInfo['parameters']['action'])
    {
      return '@homepage';
    }

    return $value;
  }
}
