<?php

/**
 * MemberConfigPassword form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigPasswordForm extends MemberConfigForm
{
  protected $category = 'password';

  public function configure()
  {
    $this->setWidget('now_password', new sfWidgetFormInputPassword());
    $this->setValidator('now_password', new sfValidatorCallback(array('callback' => array($this, 'isValidPassword'))));
    $this->widgetSchema->setLabel('now_password', '現在のパスワード');
  }

  public function isValidPassword($validator, $value)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    if (md5($value) !== MemberConfigPeer::retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'invalid', array('value' => $value));
    }

    return $value;
  }
}
