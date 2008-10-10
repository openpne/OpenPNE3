<?php

/**
 * AuthenticationPcAddress form base class.
 *
 * @package    form
 * @subpackage authentication_pc_address
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseAuthenticationPcAddressForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'member_id'        => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => true)),
      'register_session' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorPropelChoice(array('model' => 'AuthenticationPcAddress', 'column' => 'id', 'required' => false)),
      'member_id'        => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'register_session' => new sfValidatorString(array('max_length' => 32, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('authentication_pc_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AuthenticationPcAddress';
  }


}
