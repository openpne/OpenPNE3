<?php

/**
 * MemberRelationship form base class.
 *
 * @package    form
 * @subpackage member_relationship
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMemberRelationshipForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'member_id_to'    => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => false)),
      'member_id_from'  => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => false)),
      'is_friend'       => new sfWidgetFormInputCheckbox(),
      'is_friend_pre'   => new sfWidgetFormInputCheckbox(),
      'is_access_block' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'MemberRelationship', 'column' => 'id', 'required' => false)),
      'member_id_to'    => new sfValidatorDoctrineChoice(array('model' => 'Member')),
      'member_id_from'  => new sfValidatorDoctrineChoice(array('model' => 'Member')),
      'is_friend'       => new sfValidatorBoolean(array('required' => false)),
      'is_friend_pre'   => new sfValidatorBoolean(array('required' => false)),
      'is_access_block' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_relationship[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberRelationship';
  }

}