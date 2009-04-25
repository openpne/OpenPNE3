<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MemberImage filter form base class.
 *
 * @package    filters
 * @subpackage MemberImage *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberImageFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => true)),
      'file_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'File', 'add_empty' => true)),
      'is_primary' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'member_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'file_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'File', 'column' => 'id')),
      'is_primary' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('member_image_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberImage';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'member_id'  => 'ForeignKey',
      'file_id'    => 'ForeignKey',
      'is_primary' => 'Boolean',
    );
  }
}