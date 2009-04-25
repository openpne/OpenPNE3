<?php

/**
 * CommunityMember form base class.
 *
 * @package    form
 * @subpackage community_member
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCommunityMemberForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'community_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Community', 'add_empty' => false)),
      'member_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => false)),
      'position'     => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => 'CommunityMember', 'column' => 'id', 'required' => false)),
      'community_id' => new sfValidatorDoctrineChoice(array('model' => 'Community')),
      'member_id'    => new sfValidatorDoctrineChoice(array('model' => 'Member')),
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