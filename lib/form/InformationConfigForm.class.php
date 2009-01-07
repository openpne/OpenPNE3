<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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
