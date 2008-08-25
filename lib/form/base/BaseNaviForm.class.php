<?php

/**
 * Navi form base class.
 *
 * @package    form
 * @subpackage navi
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseNaviForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInputHidden(),
      'type' => new sfWidgetFormInput(),
      'uri'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorPropelChoice(array('model' => 'Navi', 'column' => 'id', 'required' => false)),
      'type' => new sfValidatorString(array('max_length' => 64)),
      'uri'  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('navi[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Navi';
  }

  public function getI18nModelName()
  {
    return 'NaviI18n';
  }

  public function getI18nFormClass()
  {
    return 'NaviI18nForm';
  }

}
