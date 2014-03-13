<?php

/**
 * IntroFriendUnread form base class.
 *
 * @method IntroFriendUnread getObject() Returns the current form's model object
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseIntroFriendUnreadForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id'  => new sfWidgetFormInputHidden(),
      'read_at'    => new sfWidgetFormDateTime(),
      'count'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'member_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('member_id')), 'empty_value' => $this->getObject()->get('member_id'), 'required' => false)),
      'read_at'    => new sfValidatorDateTime(),
      'count'      => new sfValidatorInteger(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('intro_friend_unread[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'IntroFriendUnread';
  }

}
