<?php

/**
 * sfOpenPNEAuthForm_LoginID represents a form to login.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNEAuthForm_LoginID extends sfOpenPNEAuthForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'login_id' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidatorSchema(new sfValidatorSchema(array(
      'login_id' => new sfValidatorString(),
      'password' => new sfValidatorString(),
    )));

    $this->widgetSchema->setNameFormat('auth[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('authform_login_id');
  }

  public function setForRegisterWidgets()
  {
    $this->validatorSchema['password_confirm'] = new sfValidatorString();
    $this->widgetSchema['password_confirm'] = new sfWidgetFormInputPassword();

    $this->mergePostValidator(new sfValidatorSchemaCompare('password', '==', 'password_confirm'));
    $this->mergePostValidator(new sfValidatorPropelUnique(array('model' => 'AuthenticationLoginId', 'column' => 'login_id')));
  }
}
