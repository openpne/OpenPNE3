<?php

/**
 * AdminUser form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
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

    $this->mergePostValidator(new sfValidatorPropelUnique(array(
      'model' => 'AdminUser', 'column' => array('username')
    )));
  }
}
