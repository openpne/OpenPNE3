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
 * InformationConfig form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class InformationConfigForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'information' => new sfWidgetFormTextarea(),
      'target' => new sfWidgetFormInputHidden(),
    ));
    $this->setValidators(array(
      'information' => new sfValidatorString(array('required' => false)),
      'target' => new sfValidatorString(array('required' => false)),
    ));

    $defaults = array(
      'target' => $this->getTarget(),
    );

    $config = SnsConfigPeer::retrieveByName($this->getTargetInformation());
    if ($config) {
      $defaults['information'] = $config->getValue();
    }

    $this->setDefaults($defaults);

    $this->getWidgetSchema()->setNameFormat('information[%s]');
  }

  public function save()
  {
    $config = SnsConfigPeer::retrieveByName($this->getTargetInformation());
    if (!$config) {
      $config = new SnsConfig();
      $config->setName($this->getTargetInformation());
    }

    $config->setValue($this->getValue('information'));
    $config->save();
  }

  private function getTarget()
  {
    return $this->getOption('target', 'pc_home');
  }

  private function getTargetInformation()
  {
    return $this->getTarget().'_information';
  }
}
