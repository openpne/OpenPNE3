<?php

/**
 * SnsConfig form.
 *
 * @package    form
 * @subpackage sns_config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class SnsConfigForm extends sfForm
{
  public function configure()
  {
    $widgets = array();
    $validators = array();
    $labels = array();
    $defaults = array();

    foreach (sfConfig::get('openpne_sns_config') as $key => $value) {
      $default = $value['default'];
      $config = SnsConfigPeer::retrieveByName($key);
      if ($config) {
        $default = $config->getValue();
      }

      $widgets[$key] = $this->generateWidget($value);
      $validators[$key] = $this->generateValidator($value);
      $labels[$key] = $value['caption'];
      $defaults[$key] = OpenPNEConfig::get($key, 'sns', $default);
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
      case 'select':
        $obj = new sfWidgetFormSelect(array('choices' => $this->generateChoices($config['choices_type'])));
        break;
      case 'selectMany':
        $obj = new sfWidgetFormSelectMany(array('choices' => $this->generateChoices($config['choices_type'])));
        break;
      case 'input':
      default:
        $obj = new sfWidgetFormInput();
    }

    return $obj;
  }

  public function generateValidator($config)
  {
    switch ($config['type']) {
      case 'select':
      case 'selectMany':
        $obj = new sfValidatorChoiceMany(array('choices' => $this->generateChoices($config['choices_type'])));
        break;
      case 'input':
      default:
        $obj = new sfValidatorString($config['option'], 'trim' => true);
    }

    return $obj;
  }

  public function save()
  {
    $config = sfConfig::get('openpne_sns_config');
    foreach ($this->getValues() as $key => $value) {
      $snsConfig = SnsConfigPeer::retrieveByName($key);
      if (!$snsConfig) {
        $snsConfig = new SnsConfig();
        $snsConfig->setName($key);
      }

      $snsConfig->setValue($value);
      $snsConfig->save();
    }
  }

  public function generateChoices($mode)
  {
    if ($mode == 'AuthMode') {
      return $this->generateAuthModeChoices();
    }
  }

  private function generateAuthModeChoices()
  {
    $authModes = array();

    $authPlugins = sfFinder::type('directory')->name('opAuth*Plugin')->in(sfConfig::get('sf_plugins_dir'));
    foreach ($authPlugins as $authPlugin) {
      $pluginName = basename($authPlugin);
      $endPoint = strlen($pluginName) - strlen('opAuth') - strlen('Plugin');
      $authMode = substr($pluginName, strlen('opAuth'), $endPoint);
      $authModes[$authMode] = $authMode;
    }

    return $authModes;
  }
}
