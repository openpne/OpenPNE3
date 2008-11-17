<?php

/**
 * SnsConfig form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseSnsConfigForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'name'  => new sfWidgetFormInput(),
      'value' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorPropelChoice(array('model' => 'SnsConfig', 'column' => 'id', 'required' => false)),
      'name'  => new sfValidatorString(array('max_length' => 64)),
      'value' => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'SnsConfig', 'column' => array('name')))
    );

    $this->widgetSchema->setNameFormat('sns_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SnsConfig';
  }


}
