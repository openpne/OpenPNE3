<?php

/**
 * AdminUserEditPasswordForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class AdminUserEditPasswordForm extends AdminUserForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'old_password' => new sfWidgetFormInputPassword(),
      'new_password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'old_password' => new sfValidatorString(array('max_length' => 40, 'trim' => true)),
      'new_password' => new sfValidatorString(array('max_length' => 40, 'trim' => true)),
    ));

    $this->widgetSchema->setNameFormat('admin_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
      'callback'  => array('AdminUserEditPasswordForm', 'validate'),
      'arguments' => array('object' => $this->getObject()),
    )));
  }

  public static function validate($validator, $values, $arguments = array())
  {
    if ($arguments['object']->getPassword() === md5($values['old_password']))
    {
      $values['username'] = $arguments['object']->getUsername();
      $values['password'] = $values['new_password'];
      return $values;
    }

    throw new sfValidatorError($validator, 'invalid');
  }
}
