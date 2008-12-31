<?php

/**
 * AdminUser form.
 *
 * @package    form
 * @subpackage admin_user
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class AdminUserForm extends BaseAdminUserForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInput(),
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(array('max_length' => 64, 'trim' => true)),
      'password' => new sfValidatorString(array('max_length' => 40, 'trim' => true)),
    ));

    $this->widgetSchema->setNameFormat('admin_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->widgetSchema->setLabels(array(
      'username' => 'アカウント名',
      'password' => 'パスワード',
    ));
  }

  public function isValid()
  {
    $isValid = parent::isValid();
    if (!$isValid) {
      return false;
    }

    $admin_user = AdminUserPeer::retrieveByUsername($this->getValue('username'));
    if (!$admin_user) {
      return false;
    }

    return $admin_user->getPassword() == md5($this->getValue('password'));
  }
}
