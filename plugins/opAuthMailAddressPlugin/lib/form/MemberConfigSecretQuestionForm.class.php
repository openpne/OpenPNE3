<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigSecretQuestionForm
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigSecretQuestionForm extends MemberConfigForm
{
  protected $category = 'secretQuestion';

  public function configure()
  {
    $this->setWidget('now_password', new sfWidgetFormInputPassword());
    $this->setValidator('now_password', new sfValidatorCallback(array('required' => true, 'callback' => array($this, 'isValidPassword'))));
    $this->widgetSchema->setLabel('now_password', 'Your current password');

    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'filterAnswer')))
    );
  }

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($member, $options, $CSRFSecret);

    // Hack for non-rendering secret answer
    $this->widgetSchema['secret_answer']->setOption('type', 'text');
  }

  public function setDefault($name, $default)
  {
    if ('secret_answer' === $name)
    {
      return $this;
    }

    return parent::setDefault($name, $default);
  }

  public function filterAnswer($validator, $value)
  {
    $value['secret_answer'] = md5($value['secret_answer']);

    return $value;
  }

  public function isValidPassword($validator, $value)
  {
    $member = sfContext::getInstance()->getUser()->getMember();
    if (md5($value) !== Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('password', $member->getId())->getValue())
    {
      throw new sfValidatorError(new sfValidatorPass(), 'invalid', array('value' => $value));
    }

    return $value;
  }

  public function save()
  {
    unset($this->values['now_password']);

    parent::save();
  }
}
