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
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    if (!array_key_exists('category', $options)) {
      throw new RuntimeException(sprintf('%s requires the following option: \'category\'.', get_class($this)));
    }

    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function configure()
  {
    $sfUser = sfContext::getInstance()->getUser();
    $config = OpenPNEConfig::loadConfigYaml('member');
    $memberConfig = array();

    $widgets = array();
    $validators = array();
    $labels = array();
    $defaults = array();

    if (array_key_exists('all', $config)) {
      $memberConfig += $config['all'];
    }

    if (array_key_exists(sfConfig::get('sf_app'), $config)) {
      $memberConfig += $config[sfConfig::get('sf_app')];
    }

    foreach ($memberConfig[$this->options['category']] as $key => $value) {
      $widgets[$key] = $this->generateWidget($value);
      $validators[$key] = $this->generateValidator($value);
      $labels[$key] = $value['Caption'];
      $defaults[$key] = OpenPNEConfig::get($key, 'member', MemberConfigPeer::retrieveByNameAndMemberId($key, $sfUser->getMemberId())->getValue());
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);
    $this->widgetSchema->setLabels($labels);
    $this->setDefaults($defaults);

    $this->widgetSchema->setNameFormat('member_config[%s]');
  }

  public function save($memberId)
  {
    foreach ($this->getValues() as $key => $value) {
      $memberConfig = MemberConfigPeer::retrieveByNameAndMemberId($key, $memberId);
      $memberConfig->setValue($value);
      $memberConfig->save();
    }
  }
}
