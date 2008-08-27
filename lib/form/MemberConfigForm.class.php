<?php

/**
 * MemberConfig form.
 *
 * @package    form
 * @subpackage member_config
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class MemberConfigForm extends OpenPNEFormAutoGenerate
{
  private $memberConfigSettings = array();

  public function configure()
  {
    $config = OpenPNEConfig::loadConfigYaml('member');

    if (array_key_exists('all', $config)) {
      $this->memberConfigSettings += $config['all'];
    }

    if (array_key_exists(sfConfig::get('sf_app'), $config)) {
      $this->memberConfigSettings += $config[sfConfig::get('sf_app')];
    }
  }

  public function setMemberConfigWidgets($settings, $memberId)
  {
    $widgets = array();
    $validators = array();
    $labels = array();
    $defaults = array();

    foreach ($settings as $key => $value) {
      $widgets[$key] = $this->generateWidget($value);
      $validators[$key] = $this->generateValidator($value);
      $labels[$key] = $value['Caption'];
      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($key, $memberId);
      if ($memberConfig) {
        $defaults[$key] = $memberConfig->getValue();
      }
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);
    $this->widgetSchema->setLabels($labels);
    $this->setDefaults($defaults);

    $this->widgetSchema->setNameFormat('member_config[%s]');
  }

  public function setConfigWidgets($category = null, $memberId = 0)
  {
    $settings = array();

    foreach ($this->getSettings($category) as $key => $value) {
      if ($value['IsConfig']) {
        $settings[$key] = $value;
      }
    }

    $this->setMemberConfigWidgets($settings, $memberId);
  }

  public function setRegisterWidgets($category = null, $memberId = 0)
  {
    $settings = array();

    foreach ($this->getSettings($category) as $key => $value) {
      if ($value['IsRegist']) {
        $settings[$key] = $value;
      }
    }

    $this->setMemberConfigWidgets($settings, $memberId);
  }

  public function getSettings($category = null)
  {
    if (is_null($category)) {
      $result = array();
      foreach ($this->memberConfigSettings as $value) {
        $result += $value;
      }
      return $result;
    }

    return $this->memberConfigSettings[$category];
  }

  public function save($memberId)
  {
    foreach ($this->getValues() as $key => $value) {
      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($key, $memberId);
      if ($memberConfig) {
        $memberConfig = new MemberConfig();
        $memberConfig->setName($name);
        $memberConfig->setMemberId($memberId);
      }
      $memberConfig->setValue($value);
      $memberConfig->save();
    }

    return true;
  }
}
