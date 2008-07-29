<?php

/**
 * Profile form base class.
 *
 * @package    form
 * @subpackage profile
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseProfileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'name'           => new sfWidgetFormInput(),
      'is_required'    => new sfWidgetFormInputCheckbox(),
      'is_unique'      => new sfWidgetFormInputCheckbox(),
      'form_type'      => new sfWidgetFormInput(),
      'value_type'     => new sfWidgetFormInput(),
      'value_regexp'   => new sfWidgetFormTextarea(),
      'value_min'      => new sfWidgetFormInput(),
      'value_max'      => new sfWidgetFormInput(),
      'is_disp_regist' => new sfWidgetFormInputCheckbox(),
      'is_disp_config' => new sfWidgetFormInputCheckbox(),
      'is_disp_search' => new sfWidgetFormInputCheckbox(),
      'sort_order'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'Profile', 'column' => 'id', 'required' => false)),
      'name'           => new sfValidatorString(array('max_length' => 64)),
      'is_required'    => new sfValidatorBoolean(),
      'is_unique'      => new sfValidatorBoolean(),
      'form_type'      => new sfValidatorString(array('max_length' => 32)),
      'value_type'     => new sfValidatorString(array('max_length' => 32)),
      'value_regexp'   => new sfValidatorString(array('required' => false)),
      'value_min'      => new sfValidatorInteger(array('required' => false)),
      'value_max'      => new sfValidatorInteger(array('required' => false)),
      'is_disp_regist' => new sfValidatorBoolean(),
      'is_disp_config' => new sfValidatorBoolean(),
      'is_disp_search' => new sfValidatorBoolean(),
      'sort_order'     => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Profile', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

  public function getI18nModelName()
  {
    return 'ProfileI18n';
  }

  public function getI18nFormClass()
  {
    return 'ProfileI18nForm';
  }

}
