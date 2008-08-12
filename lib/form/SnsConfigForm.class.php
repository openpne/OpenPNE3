<?php

/**
 * SnsConfig form.
 *
 * @package    form
 * @subpackage sns_config
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class SnsConfigForm extends sfForm
{
  public function configure()
  {
    $widgets = array();
    $validators = array();
    $labels = array();
    $defaults = array();

    foreach (OpenPNEConfig::loadConfigYaml() as $key => $value) {
      $widgets[$key] = $this->generateWidget($value);
      $validators[$key] = $this->generateValidator($value);
      $labels[$key] = $value['caption'];
      $defaults[$key] = OpenPNEConfig::get($key, 'sns', SnsConfigPeer::retrieveByName($key)->getValue());
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);
    $this->widgetSchema->setLabels($labels);
    $this->setDefaults($defaults);

    $this->widgetSchema->setNameFormat('sns_config[%s]');
  }

  public function generateWidget($config)
  {
    switch ($config['type']) {
      case 'input':
      default:
        $obj = new sfWidgetFormInput();
    }

    return $obj;
  }

  public function generateValidator($config)
  {
    switch ($config['type']) {
      case 'input':
      default:
        $obj = new sfValidatorString($config['option']);
    }

    return $obj;
  }

  public function save()
  {
    foreach ($this->getValues() as $key => $value) {
      $snsConfig = SnsConfigPeer::retrieveByName($key);
      $snsConfig->setValue($value);
      $snsConfig->save();
    }
  }
}
