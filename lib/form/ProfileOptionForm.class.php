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
 * ProfileOption form.
 *
 * @package    form
 * @subpackage profile_option
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileOptionForm extends BaseProfileOptionForm
{
  public function configure()
  {
    $options = array();
    if ($this->object && $this->object->getId()) {
      $options['id_format'] = '%s' . $this->object->getId();
    }

    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'profile_id' => new sfWidgetFormInputHidden(),
      'sort_order' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'ProfileOption', 'column' => 'id', 'required' => false)),
      'profile_id'         => new sfValidatorPropelChoice(array('model' => 'Profile', 'column' => 'id', 'required' => true)),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option[%s]');

    $this->embedI18n(array('ja_JP'));
  }
}
