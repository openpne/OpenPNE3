<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfig form.
 *
 * @package    form
 * @subpackage member_config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigForm extends BaseForm
{
  protected $memberConfigSettings = array();
  protected $category = '';
  protected $member;
  protected $isNew = false;
  protected $isAutoGenerate = true;

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    $this->setMemberConfigSettings();

    $this->member = $member;
    if (is_null($this->member)) {
      $this->isNew = true;
      $this->member = new Member();
      $this->member->setIsActive(false);
    } elseif (!$this->member->getIsActive()) {
      $this->isNew = true;
    }

    parent::__construct(array(), $options, $CSRFSecret);

    if ($this->isAutoGenerate) {
      $this->generateConfigWidgets();
    }

    $this->widgetSchema->setNameFormat('member_config[%s]');
  }

  public function generateConfigWidgets()
  {
    foreach ($this->memberConfigSettings as $key => $value) {
      if ($this->isNew && $value['IsRegist'] || !$this->isNew && $value['IsConfig']) {
        $this->setMemberConfigWidget($key);
      }
    }

    if ('mobile_frontend' === sfConfig::get('sf_app'))
    {
      $this->appendMobileInputMode();
    }
  }

  protected function appendMobileInputMode()
  {
    parent::appendMobileInputMode();

    foreach ($this as $k => $v)
    {
      $widget = $this->widgetSchema[$k];
      $validator = $this->validatorSchema[$k];

      if (!($widget instanceof sfWidgetFormInput))
      {
        continue;
      }

      if ($validator instanceof sfValidatorAnd)
      {
        foreach ($validator->getValidators() as $childValidator)
        {
          if ($childValidator instanceof sfValidatorEmail)
          {
            opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'alphabet');
          }
          elseif ($childValidator instanceof sfValidatorNumber)
          {
            opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'numeric');
          }
        }
      }
    }
  }

  public function setMemberConfigSettings()
  {
    $categories = sfConfig::get('openpne_member_category');
    $configs = sfConfig::get('openpne_member_config');

    if (!$this->category) {
      $this->memberConfigSettings = $configs;
      return true;
    }

    foreach ($categories[$this->category] as $value)
    {
      $this->memberConfigSettings[$value] = $configs[$value];
    }
  }

  public function setMemberConfigWidget($name)
  {
    $config = $this->memberConfigSettings[$name];
    $this->widgetSchema[$name] = opFormItemGenerator::generateWidget($config);
    $this->widgetSchema->setLabel($name, $config['Caption']);
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name, $this->member->getId());
    if ($memberConfig) {
      $this->setDefault($name, $memberConfig->getValue());
    }
    $this->validatorSchema[$name] = opFormItemGenerator::generateValidator($config);

    if (!empty($config['IsConfirm'])) {
      $this->validatorSchema[$name.'_confirm'] = $this->validatorSchema[$name];
      $this->widgetSchema[$name.'_confirm'] = $this->widgetSchema[$name];
      $this->widgetSchema->setLabel($name.'_confirm', $config['Caption'].' (Confirm)');

      $this->mergePostValidator(new sfValidatorSchemaCompare($name, '==', $name.'_confirm'));
    }

    if (!empty($config['IsUnique'])) {
      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'isUnique'),
        'arguments' => array('name' => $name),
      )));
    }

    if (!empty($config['Info']))
    {
      $this->widgetSchema->setHelp($name, $config['Info']);
    }
  }

  public function isUnique($validator, $value, $arguments = array())
  {
    if (empty($arguments['name'])) {
      throw new InvalidArgumentException('Invalid argument');
    }

    $name = $arguments['name'];
    $data = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($name, $value[$name]);
    if (!$data || !$data->getMember()->getIsActive() || $data->getMember()->getId() == $this->member->getId()) {
      return $value;
    }

    throw new sfValidatorError($validator, 'Invalid %name%.', array('name' => $name));
  }

  public function isValid()
  {
    if ($this->member)
    {
      return parent::isValid();
    }

    opActivateBehavior::disable();

    foreach ($this->getValues() as $key => $value)
    {
      if (!empty($this->memberConfigSettings[$key]['IsUnique']))
      {
        $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($key.'_pre', $value);
        if ($memberConfig)
        {
          $member = $memberConfig->getMember();
          if (!$member->getIsActive())
          {
            $this->member = $member;
          }
        }
      }
    }

    opActivateBehavior::enable();
    return parent::isValid();
  }

  public function save()
  {
    $this->member->save();

    foreach ($this->getValues() as $key => $value)
    {
      if (strrpos($key, '_confirm'))
      {
        continue;
      }

      $this->saveConfig($key, $value);
    }

    return true;
  }

  public function saveConfig($name, $value)
  {
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name, $this->member->getId());
    if (!$memberConfig) {
      $memberConfig = new MemberConfig();
      $memberConfig->setName($name);
      $memberConfig->setMember($this->member);
    }
    $memberConfig->setValue($value);

    $memberConfig->save();
  }

  public function savePreConfig($name, $value)
  {
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name.'_pre', $this->member->getId());
    if (!$memberConfig) {
      $memberConfig = new MemberConfig();
      $memberConfig->setName($name);
      $memberConfig->setMember($this->member);
    }

    $memberConfig->setValue($value);
    $memberConfig->savePre();
    $memberConfig->saveToken();
  }

  public function getCompleteMessage()
  {
    return 'Saved configuration successfully.';
  }
}
