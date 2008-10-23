<?php

/**
 * sfOpenPNEPassword form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEPasswordForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'password' => new sfWidgetFormInputPassword(),
    ));

    $this->setValidators(array(
      'password' => new sfValidatorCallback(array('callback' => array($this, 'isValidPassword'))),
    ));

    $this->widgetSchema->setNameFormat('password[%s]');
  }

  public function isValidPassword($validator, $value)
  {
    $member = $this->options['member'];
    if (md5($value) !== MemberConfigPeer::retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'invalid', array('value' => $value));
    }

    return $value;
  }
}
