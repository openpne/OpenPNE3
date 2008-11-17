<?php

/**
 * ProfileOptionI18n form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseProfileOptionI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'value'   => new sfWidgetFormTextarea(),
      'id'      => new sfWidgetFormInputHidden(),
      'culture' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'value'   => new sfValidatorString(array('required' => false)),
      'id'      => new sfValidatorPropelChoice(array('model' => 'ProfileOption', 'column' => 'id', 'required' => false)),
      'culture' => new sfValidatorPropelChoice(array('model' => 'ProfileOptionI18n', 'column' => 'culture', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileOptionI18n';
  }


}
