<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CommunityConfig filter form base class.
 *
 * @package    filters
 * @subpackage CommunityConfig *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCommunityConfigFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'community_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Community', 'add_empty' => true)),
      'name'         => new sfWidgetFormFilterInput(),
      'value'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'community_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Community', 'column' => 'id')),
      'name'         => new sfValidatorPass(array('required' => false)),
      'value'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('community_config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CommunityConfig';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'community_id' => 'ForeignKey',
      'name'         => 'Text',
      'value'        => 'Text',
    );
  }
}