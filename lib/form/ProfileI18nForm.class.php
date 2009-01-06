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
 * ProfileI18n form.
 *
 * @package    form
 * @subpackage profile_i18n
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileI18nForm extends BaseProfileI18nForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'caption' => new sfWidgetFormInput(),
      'info' => new sfWidgetFormInput(),
    ));

    $this->widgetSchema->setLabels(array(
      'caption' => '項目名',
      'info' => '説明',
    ));
  }
}
