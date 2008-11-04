<?php

/**
 * FriendPre form base class.
 *
 * @package    form
 * @subpackage friend_pre
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseFriendPreForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'member_id_to'   => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => false)),
      'member_id_from' => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'FriendPre', 'column' => 'id', 'required' => false)),
      'member_id_to'   => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
      'member_id_from' => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'FriendPre', 'column' => array('member_id_to', 'member_id_from')))
    );

    $this->widgetSchema->setNameFormat('friend_pre[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'FriendPre';
  }


}
