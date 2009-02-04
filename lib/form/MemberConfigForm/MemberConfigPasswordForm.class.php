<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
    $this->setValidator(
      'now_password',
      new sfValidatorCallback(
        array('callback' => array($this, 'isValidNowPassword'), 'required' => true)
      )
    );
    $this->widgetSchema->setLabel('now_password', 'Your current password');
  }

  public function setMemberConfigWidget($name)
  {
    $result = parent::setMemberConfigWidget($name);

    if ($name === 'password')
    {
      $this->setValidator( $name, new sfValidatorCallback(array(
        'callback'  => array($this, 'isValidPassword'),
        'required' => true
      )));
    }

    return $result;
  }

  public function isValidNowPassword($validator, $value)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    if (md5($value) !== MemberConfigPeer::retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'Invalid.', array('value' => $value));
    }

    return $value;
  }

  public function isValidPassword($validator, $value)
  {
    $size = strlen($value);
    if (!preg_match("/^[a-zA-Z0-9]+$/", $value) || $size < 6 || $size > 12)
    {
      throw new sfValidatorError(new sfValidatorPass(), 'Please input by a-z or A-Z or 6-12 characters and 6-12 length.', array('value' => $value));
    }

    return $value;
  }

  public function save()
  {
    $this->values['password'] = md5($this->values['password']);
    parent::save();
  }
}
