<?php

/**
 * sfOpenPNEAuthForm_PCAddress represents a form to login.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNEAuthForm_PCAddress extends sfOpenPNEAuthForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'pc_address' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidatorSchema(new sfValidatorSchema(array(
      'pc_address' => new sfValidatorEmail(),
      'password' => new sfValidatorString(),
    )));

    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('authform_pc_address');

    parent::configure();
  }

  public function setForRegisterWidgets($member = null)
  {
    parent::setForRegisterWidgets($member);

    $this->validatorSchema['password_confirm'] = new sfValidatorString();
    $this->widgetSchema['password_confirm'] = new sfWidgetFormInputPassword();

    $this->mergePostValidator(new sfValidatorSchemaCompare('password', '==', 'password_confirm'));

    // FIXME
    unset($this->configForm->validatorSchema['pc_address']);
    unset($this->configForm->widgetSchema['pc_address']);
    unset($this->validatorSchema['pc_address']);
    unset($this->widgetSchema['pc_address']);
  }
}
