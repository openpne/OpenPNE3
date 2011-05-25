<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigAccessBlockForm form.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigAccessBlockForm extends BaseForm
{
  protected $member;

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct(array(), $options, $CSRFSecret);

    $this->member = $member;

    $this->widgetSchema['access_block'] = new sfWidgetFormInput();
    $this->widgetSchema->setLabel('access_block', 'Member ID');

    $validatorCallback = new sfValidatorCallback(array('callback' => array($this, 'validate')));
    $validatorInteger = new sfValidatorInteger(array('min' => 1), array('invalid' => 'Invalid.', 'min' => 'Invalid.'));

    $this->validatorSchema['access_block'] = new sfValidatorAnd(array($validatorInteger, $validatorCallback));



    if ('mobile_frontend' === sfConfig::get('sf_app'))
    {
      opToolkit::appendMobileInputModeAttributesForFormWidget($this->widgetSchema['access_block'], 'numeric');
      $this->widgetSchema['access_block']->setAttribute('size', 8);
    }
    $this->widgetSchema->setNameFormat('member_config[%s]');
  }

  public function save()
  {
    $this->member->save();
    $relationship = Doctrine::getTable('MemberRelationship')
          ->retrieveByFromAndTo($this->member->getId(), $this->getValue('access_block'));
    if (!$relationship)
    {
      $relationship = new MemberRelationship();
      $relationship->setMemberIdFrom($this->member->getId());
      $relationship->setMemberIdTo($this->getValue('access_block'));
    }
    $relationship->setIsAccessBlock(true);
    $relationship->save();
    return true;
  }

  public function validate($validator, $value)
  {
    if (sfContext::getInstance()->getUser()->getMemberId() == $value)
    {
      throw new sfValidatorError($validator, 'It\'s your member ID.');
    }
    if (preg_match('/^[1-9]\d*$/', $value) && !Doctrine::getTable('Member')->find($value))
    {
      throw new sfValidatorError($validator, 'The member ID was deleted or do not exist.');
    }
    $relationship = Doctrine::getTable('MemberRelationship')
          ->retrieveByFromAndTo(sfContext::getInstance()->getUser()->getMemberId(), $value);
    if ($relationship && $relationship->getIsAccessBlock())
    {
      throw new sfValidatorError($validator, 'You have already blocked the member ID.');
    }
    return $value;
  }

  public function getCompleteMessage()
  {
    return sfContext::getInstance()->getI18N()->__('Member ID: %id% was added.', array('%id%' => $this->getValue('access_block')));
  }
}
