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
 * opAuthValidatorMemberConfigAndPassword
 *
 * @package    OpenPNE
 * @subpackage validator
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAuthValidatorMemberConfigAndPassword extends opAuthValidatorMemberConfig
{
  /**
   * @see opAuthValidatorMemberConfig
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->setMessage('invalid', 'ID or password is not a valid.');
  }

  /**
   * @see opAuthValidatorMemberConfig
   */
  protected function doClean($values)
  {
    opActivateBehavior::disable();
    $values = parent::doClean($values);

    if (empty($values['member']) || !($values['member'] instanceof Member))
    {
      throw new sfValidatorError($this, 'invalid');
      opActivateBehavior::enable();
    }

    $valid_password = MemberConfigPeer::retrieveByNameAndMemberId('password', $values['member']->getId())->getValue();
    opActivateBehavior::enable();
    if (md5($values['password']) !== $valid_password)
    {
      throw new sfValidatorError($this, 'invalid');
    }

    return $values;
  }
}
