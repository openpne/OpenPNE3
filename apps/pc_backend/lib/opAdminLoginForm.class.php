<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opAdminLoginForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opAdminLoginForm extends sfForm
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

    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
      'callback' => array('opAdminLoginForm', 'validate'),
    )));
  }

  public static function validate($validator, $values, $arguments = array())
  {
    $adminUser = Doctrine::getTable('AdminUser')->retrieveByUsername($values['username']);
    if (!$adminUser)
    {
      throw new sfValidatorError($validator, 'invalid');
    }

    if ($adminUser->getPassword() === md5($values['password']))
    {
      $values['adminUser'] = $adminUser;
      return $values;
    }

    throw new sfValidatorError($validator, 'invalid');
  }
}
