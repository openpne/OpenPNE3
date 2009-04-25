<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CommunityMember filter form base class.
 *
 * @package    filters
 * @subpackage CommunityMember *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCommunityMemberFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'community_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Community', 'add_empty' => true)),
      'member_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => true)),
      'position'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'community_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Community', 'column' => 'id')),
      'member_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'position'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community_member_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityMember';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'community_id' => 'ForeignKey',
      'member_id'    => 'ForeignKey',
      'position'     => 'Text',
    );
  }
}