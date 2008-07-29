<?php

/**
 * CommunityMember form base class.
 *
 * @package    form
 * @subpackage community_member
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 8807 2008-05-06 14:12:28Z fabien $
 */
class BaseCommunityMemberForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'community_id' => new sfWidgetFormPropelSelect(array('model' => 'Community', 'add_empty' => false)),
      'member_id'    => new sfWidgetFormPropelSelect(array('model' => 'Member', 'add_empty' => false)),
      'position'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'CommunityMember', 'column' => 'id', 'required' => false)),
      'community_id' => new sfValidatorPropelChoice(array('model' => 'Community', 'column' => 'id')),
      'member_id'    => new sfValidatorPropelChoice(array('model' => 'Member', 'column' => 'id')),
      'position'     => new sfValidatorString(array('max_length' => 32, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community_member[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityMember';
  }


}
