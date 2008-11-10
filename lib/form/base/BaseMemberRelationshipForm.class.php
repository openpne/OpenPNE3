<?php

/**
 * MemberRelationship form base class.
 *
 * @package    form
 * @subpackage member_relationship
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseMemberRelationshipForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'member_id_to'   => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => false)),
      'member_id_from' => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => false)),
      'is_friend'      => new sfWidgetFormInputCheckbox(),
      'is_friend_pre'  => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'MemberRelationship', 'column' => 'id', 'required' => false)),
      'member_id_to'   => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
      'member_id_from' => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
      'is_friend'      => new sfValidatorBoolean(array('required' => false)),
      'is_friend_pre'  => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'MemberRelationship', 'column' => array('member_id_to', 'member_id_from'))),
        new sfValidatorPropelUnique(array('model' => 'MemberRelationship', 'column' => array('member_id_from', 'member_id_to'))),
      ))
    );

    $this->widgetSchema->setNameFormat('member_relationship[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberRelationship';
  }


}
