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
 * opAuthValidatorMemberConfig
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthValidatorMemberConfig extends sfValidatorSchema
{
  /**
   * Constructor.
   *
   * @param array  $options   An array of options
   * @param array  $messages  An array of error messages
   *
   * @see sfValidatorSchema
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

  /**
   * Configures this validator.
   *
   * Available options:
   *
   *  * config_name: The configuration name of MemberConfig
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('field_name');
    $this->addRequiredOption('config_name');
    $this->setMessage('invalid', 'ID is not a valid.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    opActivateBehavior::disable();
    $configName = $this->getOption('config_name');
    $fieldName = $this->getOption('field_name');
    if (!$fieldName)
    {
      $fieldName = $configName;
    }
    $memberConfig = MemberConfigPeer::retrieveByNameAndValue($configName, $values[$fieldName]);
    if ($memberConfig)
    {
      $values['member'] = $memberConfig->getMember();
    }

    opActivateBehavior::enable();
    return $values;
  }
}
