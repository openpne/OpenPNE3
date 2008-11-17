<?php

/**
 * ProfileI18n form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseProfileI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'caption' => new sfWidgetFormTextarea(),
      'info'    => new sfWidgetFormTextarea(),
      'id'      => new sfWidgetFormInputHidden(),
      'culture' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'caption' => new sfValidatorString(),
      'info'    => new sfValidatorString(array('required' => false)),
      'id'      => new sfValidatorPropelChoice(array('model' => 'Profile', 'column' => 'id', 'required' => false)),
      'culture' => new sfValidatorPropelChoice(array('model' => 'ProfileI18n', 'column' => 'culture', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileI18n';
  }


}
