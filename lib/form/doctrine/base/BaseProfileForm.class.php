<?php

/**
 * Profile form base class.
 *
 * @package    form
 * @subpackage profile
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormInput(),
      'is_required'         => new sfWidgetFormInputCheckbox(),
      'is_unique'           => new sfWidgetFormInputCheckbox(),
      'is_edit_public_flag' => new sfWidgetFormInputCheckbox(),
      'default_public_flag' => new sfWidgetFormInput(),
      'form_type'           => new sfWidgetFormInput(),
      'value_type'          => new sfWidgetFormInput(),
      'is_disp_regist'      => new sfWidgetFormInputCheckbox(),
      'is_disp_config'      => new sfWidgetFormInputCheckbox(),
      'is_disp_search'      => new sfWidgetFormInputCheckbox(),
      'value_regexp'        => new sfWidgetFormTextarea(),
      'value_min'           => new sfWidgetFormInput(),
      'value_max'           => new sfWidgetFormInput(),
      'sort_order'          => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'Profile', 'column' => 'id', 'required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 64)),
      'is_required'         => new sfValidatorBoolean(),
      'is_unique'           => new sfValidatorBoolean(),
      'is_edit_public_flag' => new sfValidatorBoolean(),
      'default_public_flag' => new sfValidatorInteger(),
      'form_type'           => new sfValidatorString(array('max_length' => 32)),
      'value_type'          => new sfValidatorString(array('max_length' => 32)),
      'is_disp_regist'      => new sfValidatorBoolean(),
      'is_disp_config'      => new sfValidatorBoolean(),
      'is_disp_search'      => new sfValidatorBoolean(),
      'value_regexp'        => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'value_min'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'value_max'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'sort_order'          => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

}