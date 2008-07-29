<?php

/**
 * AuthenticationLoginId form base class.
 *
 * @package    form
 * @subpackage authentication_login_id
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseAuthenticationLoginIdForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'member_id' => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => true)),
      'login_id'  => new sfWidgetFormInput(),
      'password'  => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'AuthenticationLoginId', 'column' => 'id', 'required' => false)),
      'member_id' => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'login_id'  => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'password'  => new sfValidatorString(array('max_length' => 32, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'AuthenticationLoginId', 'column' => array('login_id')))
    );

    $this->widgetSchema->setNameFormat('authentication_login_id[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AuthenticationLoginId';
  }


}
