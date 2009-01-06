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
 * sfOpenPNEPassword form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEPasswordForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'password' => new sfValidatorCallback(array('callback' => array($this, 'isValidPassword'))),
    ));

    $this->widgetSchema->setNameFormat('password[%s]');
  }

  public function isValidPassword($validator, $value)
  {
    $member = $this->options['member'];
    if (md5($value) !== MemberConfigPeer::retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'invalid', array('value' => $value));
    }

    return $value;
  }
}
