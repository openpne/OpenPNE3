<?php

/**
 * MemberProfile form base class.
 *
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 12815 2008-11-09 10:43:58Z fabien $
 */
class BaseMemberProfileForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'member_id'         => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => true)),
      'profile_id'        => new sfWidgetFormPropelChoice(array('model' => 'Profile', 'add_empty' => true)),
      'profile_option_id' => new sfWidgetFormPropelChoice(array('model' => 'ProfileOption', 'add_empty' => true)),
      'value'             => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorPropelChoice(array('model' => 'MemberProfile', 'column' => 'id', 'required' => false)),
      'member_id'         => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id', 'required' => false)),
      'profile_id'        => new sfValidatorPropelChoice(array('model' => 'Profile', 'column' => 'id', 'required' => false)),
      'profile_option_id' => new sfValidatorPropelChoice(array('model' => 'ProfileOption', 'column' => 'id', 'required' => false)),
      'value'             => new sfValidatorString(array('required' => false)),
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
