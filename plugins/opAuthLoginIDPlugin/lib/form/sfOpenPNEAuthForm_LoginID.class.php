<?php

/**
 * sfOpenPNEAuthForm_LoginID represents a form to login.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
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

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('authform_login_id');

    parent::configure();
  }

  public function setForRegisterWidgets($member = null)
  {
    parent::setForRegisterWidgets($member);

    $this->validatorSchema['password_confirm'] = new sfValidatorString();
    $this->widgetSchema['password_confirm'] = new sfWidgetFormInputPassword();

    $this->mergePostValidator(new sfValidatorSchemaCompare('password', '==', 'password_confirm'));
    $this->mergePostValidator(new sfValidatorPropelUnique(array('model' => 'AuthenticationLoginId', 'column' => 'login_id')));
  }
}
