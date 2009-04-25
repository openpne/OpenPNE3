<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CommunityCategory filter form base class.
 *
 * @package    filters
 * @subpackage CommunityCategory *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCommunityCategoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                      => new sfWidgetFormFilterInput(),
      'is_allow_member_community' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'tree_key'                  => new sfWidgetFormFilterInput(),
      'sort_order'                => new sfWidgetFormFilterInput(),
      'lft'                       => new sfWidgetFormFilterInput(),
      'rgt'                       => new sfWidgetFormFilterInput(),
      'level'                     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                      => new sfValidatorPass(array('required' => false)),
      'is_allow_member_community' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'tree_key'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sort_order'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'lft'                       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rgt'                       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'level'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('community_category_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityCategory';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'name'                      => 'Text',
      'is_allow_member_community' => 'Boolean',
      'tree_key'                  => 'Number',
      'sort_order'                => 'Number',
      'lft'                       => 'Number',
      'rgt'                       => 'Number',
      'level'                     => 'Number',
    );
  }
}