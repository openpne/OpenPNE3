<?php

/**
 * AdminUser form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseAdminUserForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'       => new sfWidgetFormInputHidden(),
      'username' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'       => new sfValidatorPropelChoice(array('model' => 'AdminUser', 'column' => 'id', 'required' => false)),
      'username' => new sfValidatorString(array('max_length' => 64)),
      'password' => new sfValidatorString(array('max_length' => 40)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'AdminUser', 'column' => array('username')))
    );

    $this->widgetSchema->setNameFormat('admin_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AdminUser';
  }


}
