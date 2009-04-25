<?php

/**
 * MemberProfile form base class.
 *
 * @package    form
 * @subpackage member_profile
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMemberProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'member_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => false)),
      'profile_id'        => new sfWidgetFormDoctrineChoice(array('model' => 'Profile', 'add_empty' => false)),
      'profile_option_id' => new sfWidgetFormDoctrineChoice(array('model' => 'ProfileOption', 'add_empty' => true)),
      'value'             => new sfWidgetFormTextarea(),
      'public_flag'       => new sfWidgetFormInput(),
      'tree_key'          => new sfWidgetFormInput(),
      'lft'               => new sfWidgetFormInput(),
      'rgt'               => new sfWidgetFormInput(),
      'level'             => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'MemberProfile', 'column' => 'id', 'required' => false)),
      'member_id'         => new sfValidatorDoctrineChoice(array('model' => 'Member')),
      'profile_id'        => new sfValidatorDoctrineChoice(array('model' => 'Profile')),
      'profile_option_id' => new sfValidatorDoctrineChoice(array('model' => 'ProfileOption', 'required' => false)),
      'value'             => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'public_flag'       => new sfValidatorInteger(array('required' => false)),
      'tree_key'          => new sfValidatorInteger(array('required' => false)),
      'lft'               => new sfValidatorInteger(array('required' => false)),
      'rgt'               => new sfValidatorInteger(array('required' => false)),
      'level'             => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberProfile';
  }

}