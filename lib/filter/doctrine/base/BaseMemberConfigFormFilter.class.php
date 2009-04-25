<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MemberConfig filter form base class.
 *
 * @package    filters
 * @subpackage MemberConfig *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMemberConfigFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'member_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Member', 'add_empty' => true)),
      'name'      => new sfWidgetFormFilterInput(),
      'value'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'member_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Member', 'column' => 'id')),
      'name'      => new sfValidatorPass(array('required' => false)),
      'value'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('member_config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MemberConfig';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'member_id' => 'ForeignKey',
      'name'      => 'Text',
      'value'     => 'Text',
    );
  }
}