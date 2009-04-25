<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MemberRelationship filter form base class.
 *
 * @package    filters
 * @subpackage MemberRelationship *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberRelationshipFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id_to'    => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => true)),
      'member_id_from'  => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => true)),
      'is_friend'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_friend_pre'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_access_block' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'member_id_to'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'member_id_from'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'is_friend'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_friend_pre'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_access_block' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('member_relationship_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberRelationship';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'member_id_to'    => 'ForeignKey',
      'member_id_from'  => 'ForeignKey',
      'is_friend'       => 'Boolean',
      'is_friend_pre'   => 'Boolean',
      'is_access_block' => 'Boolean',
    );
  }
}