<?php

/**
 * ProfileOption form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseProfileOptionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'profile_id' => new sfWidgetFormPropelChoice(array('model' => 'Profile', 'add_empty' => true)),
      'sort_order' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'ProfileOption', 'column' => 'id', 'required' => false)),
      'profile_id' => new sfValidatorPropelChoice(array('model' => 'Profile', 'column' => 'id', 'required' => false)),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileOption';
  }

  public function getI18nModelName()
  {
    return 'ProfileOptionI18n';
  }

  public function getI18nFormClass()
  {
    return 'ProfileOptionI18nForm';
  }

}
