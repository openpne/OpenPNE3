<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * MemberRelationship filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberRelationshipFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id_to'   => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => true)),
      'member_id_from' => new sfWidgetFormPropelChoice(array('model' => 'Member', 'add_empty' => true)),
      'is_friend'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_friend_pre'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'member_id_to'   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'member_id_from' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'is_friend'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_friend_pre'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
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
      'id'             => 'Text',
      'member_id_to'   => 'ForeignKey',
      'member_id_from' => 'ForeignKey',
      'is_friend'      => 'Boolean',
      'is_friend_pre'  => 'Boolean',
    );
  }
}
