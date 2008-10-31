<?php

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
      'information' => new sfWidgetFormTextarea()
    ));
    $this->setValidators(array(
      'information' => new sfValidatorString(array('required' => false))
    ));

    $config = SnsConfigPeer::retrieveByName('information');
    if ($config) {
      $this->setDefaults(array(
        'information' => $config->getValue(),
      ));
    }

    $this->widgetSchema->setNameFormat('sns_config[%s]');
  }

  public function save()
  {
    $config = SnsConfigPeer::retrieveByName('information');
    if (!$config) {
      $config = new SnsConfig();
      $config->setName('information');
    }

    $config->setValue($this->getValue('information'));
    $config->save();
  }
}
