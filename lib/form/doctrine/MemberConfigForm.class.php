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
    $this->setWidget($name, opFormItemGenerator::generateWidget($config));
    $this->widgetSchema->setLabel($name, $config['Caption']);
    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($name, $this->member->getId());
    if ($memberConfig) {
      $this->setDefault($name, $memberConfig->getValue());
    }
    $this->validatorSchema[$name] = opFormItemGenerator::generateValidator($config);

    if (!empty($config['IsUnique']))
    {
      $uniqueValidator = new sfValidatorCallback(array(
        'callback'    => array($this, 'isUnique'),
        'arguments'   => array('name' => $name),
        'empty_value' => $this->validatorSchema[$name]->getOption('empty_value'),
      ));

      $this->validatorSchema[$name] = new sfValidatorAnd(array(
        $this->validatorSchema[$name],
        $uniqueValidator,
      ), array(
        'required'      => $this->validatorSchema[$name]->getOption('required'),
        'empty_value'   => $this->validatorSchema[$name]->getOption('empty_value'),
        'halt_on_error' => true,
      ));
    }

    if (!empty($config['IsConfirm']))
    {
      $this->validatorSchema[$name.'_confirm'] = $this->validatorSchema[$name];
      $this->widgetSchema[$name.'_confirm'] = $this->widgetSchema[$name];
      $this->widgetSchema->setLabel($name.'_confirm', $config['Caption'].' (Confirm)');

      $this->mergePreValidator(new sfValidatorCallback(array(
        'callback'  => array($this, 'preValidateConfirmField'),
        'arguments' => array('name' => $name),
      )));
      $this->mergePostValidator(new sfValidatorCallback(array(
        'callback'  => array($this, 'postValidateConfirmField'),
        'arguments' => array('name' => $name, 'validator' => $this->validatorSchema[$name]),
      )));
    }

    if (!empty($config['Info']))
    {
      $this->widgetSchema->setHelp($name, $config['Info']);
    }
  }

  /**
   * IsConfirm: true のフィールド用の Pre Validator
   *
   * 個別フィールドのバリデーターを一旦 sfValidatorPass に置き換え、
   * バリデーションは Post Validator でまとめておこなう
   */
  public function preValidateConfirmField($validator, $values, $arguments = array())
  {
    $name = $arguments['name'];

    // 入力フォーム画面に「必須項目マーク(*)」を表示するためバリデーション直前で変更する
    $this->validatorSchema[$name] = $this->validatorSchema[$name.'_confirm'] = new sfValidatorPass();

    return $values;
  }

  /**
   * IsConfirm: true のフィールド用の Post Validator
   *
   * 元フィールドと _confirm フィールドの個別バリデーションもこの中でおこない、
   * エラーにならなかった場合のみ両者を比較することで、エラーが重複して表示されるのを防ぐ
   */
  public function postValidateConfirmField($validator, $values, $arguments = array())
  {
    $name = $arguments['name'];
    $fieldValidator = $arguments['validator'];

    // バリデーションエラー時のフォーム画面に「必須項目マーク(*)」を表示するためバリデーターを元に戻す
    $this->validatorSchema[$name] = $this->validatorSchema[$name.'_confirm'] = $fieldValidator;

    // 元フィールドのバリデーション
    try
    {
      $values[$name] = $fieldValidator->clean($values[$name]);
    }
    catch (sfValidatorError $e)
    {
      throw new sfValidatorErrorSchema($validator, array($name => $e));
    }

    // _confirm フィールドのバリデーション
    try
    {
      $values[$name.'_confirm'] = $fieldValidator->clean($values[$name.'_confirm']);
    }
    catch (sfValidatorError $e)
    {
      // _confirm だけエラーになる場合、2つのフィールドの値は一致していないので、
      // sfValidatorSchemaCompare と同じ invalid エラーの例外を投げる
      throw new sfValidatorErrorSchema($validator, array($name.'_confirm' => new sfValidatorError($validator, 'invalid')));
    }

    // 2つのフィールドが共にエラーでない場合のみ値を比較する
    // validator の clean() は値を変更することがあるため、clean() 後の値を比較する
    $compareValidator = new sfValidatorSchemaCompare($name.'_confirm', '===', $name);
    $values = $compareValidator->clean($values);

    return $values;
  }

  public function isUnique($validator, $value, $arguments = array())
  {
    if (empty($arguments['name'])) {
      throw new InvalidArgumentException('Invalid argument');
    }

    $name = $arguments['name'];
    $data = Doctrine::getTable('MemberConfig')->retrieveByNameAndValue($name, $value);
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
