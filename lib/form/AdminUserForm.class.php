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
 * AdminUser form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class AdminUserForm extends BaseAdminUserForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(array('max_length' => 64, 'trim' => true)),
      'password' => new sfValidatorString(array('max_length' => 40, 'trim' => true)),
    ));

    $this->widgetSchema->setNameFormat('admin_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->widgetSchema->setLabels(array(
      'username' => 'アカウント名',
      'password' => 'パスワード',
    ));

    $this->mergePostValidator(new sfValidatorPropelUnique(array(
      'model' => 'AdminUser', 'column' => array('username')
    )));
  }
}
