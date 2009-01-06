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
 * opAuthConfigForm represents a form to login.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opAuthConfigForm extends OpenPNEFormAutoGenerate
{
  protected
    $adapter = null;

  /**
   * Constructor.
   *
   * @param opAuthAdapter $adapter    An opAuthAdapter object
   * @param array         $defaults   An array of field default values
   * @param array         $options    An array of options
   * @param string        $CRFSSecret A CSRF secret (false to disable CSRF protection, null to use the global CSRF secret)
   *
   * @see sfForm
   */
  public function __construct(opAuthAdapter $adapter, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->adapter = $adapter;

    foreach ($this->adapter->getAuthConfigSettings() as $key => $value)
    {
      if (isset($defaults[$key]))
      {
        continue;
      }

      if (isset($value['IsConfig']) && !$value['IsConfig'])
      {
        $defaults[$key] = $value['Default'];
        continue;
      }

      $default = $this->adapter->getAuthConfig($key);
      if (!is_null($default))
      {
        $defaults[$key] = $default;
      }
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function setup()
  {
    foreach ($this->adapter->getAuthConfigSettings() as $key => $value)
    {
      if (isset($value['IsConfig']) && !$value['IsConfig'])
      {
        continue;
      }

      $obj = $this->generateWidget($value);
      $this->setWidget($key, $obj);
      $this->setValidator($key, $this->generateValidator($value));
    }

    $this->widgetSchema->setNameFormat('auth'.$this->adapter->getAuthModeName().'[%s]');
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value)
    {
      $this->adapter->setAuthConfig($key, $value);
    }
  }
}
