<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MemberProfile filter form base class.
 *
 * @package    filters
 * @subpackage MemberProfile *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => true)),
      'profile_id'        => new sfWidgetFormDoctrineChoice(array('model' => 'Profile', 'add_empty' => true)),
      'profile_option_id' => new sfWidgetFormDoctrineChoice(array('model' => 'ProfileOption', 'add_empty' => true)),
      'value'             => new sfWidgetFormFilterInput(),
      'public_flag'       => new sfWidgetFormFilterInput(),
      'tree_key'          => new sfWidgetFormFilterInput(),
      'lft'               => new sfWidgetFormFilterInput(),
      'rgt'               => new sfWidgetFormFilterInput(),
      'level'             => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'member_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'profile_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Profile', 'column' => 'id')),
      'profile_option_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'ProfileOption', 'column' => 'id')),
      'value'             => new sfValidatorPass(array('required' => false)),
      'public_flag'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tree_key'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('member_profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberProfile';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'member_id'         => 'ForeignKey',
      'profile_id'        => 'ForeignKey',
      'profile_option_id' => 'ForeignKey',
      'value'             => 'Text',
      'public_flag'       => 'Number',
      'tree_key'          => 'Number',
      'lft'               => 'Number',
      'rgt'               => 'Number',
      'level'             => 'Number',
    );
  }
}