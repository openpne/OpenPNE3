<?php

/**
 * sfOpenPNEAuthForm_PCAddress represents a form to login.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
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

    // FIXME
    unset($this->configForm->validatorSchema['pc_address']);
    unset($this->configForm->widgetSchema['pc_address']);
    $this->unsetFormField('pc_address');
    $this->unsetFormField('password');
  }

  private function unsetFormField($name)
  {
    unset($this->validatorSchema[$name]);
    unset($this->widgetSchema[$name]);
  }
}
