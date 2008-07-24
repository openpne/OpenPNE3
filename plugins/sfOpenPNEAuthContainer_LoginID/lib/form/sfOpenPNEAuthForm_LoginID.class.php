<?php

/**
 * sfOpenPNEAuthForm_LoginID represents a form to login.
 *
 * @package    symfony
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class sfOpenPNEAuthForm_LoginID extends sfForm
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
  }
}
