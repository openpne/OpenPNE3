<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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

    foreach (sfConfig::get('openpne_sns_config') as $key => $value)
    {
      $widgets[$key] = $this->generateWidget($value);
      $validators[$key] = $this->generateValidator($value);
      $labels[$key] = $value['caption'];
      $defaults[$key] = opConfig::get($key);
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
        $obj = new sfValidatorString(array_merge($config['option'], array('trim' => true)));
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
  }
}
