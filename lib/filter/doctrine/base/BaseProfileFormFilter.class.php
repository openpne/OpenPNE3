<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Profile filter form base class.
 *
 * @package    filters
 * @subpackage Profile *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                => new sfWidgetFormFilterInput(),
      'is_required'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_unique'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_edit_public_flag' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'default_public_flag' => new sfWidgetFormFilterInput(),
      'form_type'           => new sfWidgetFormFilterInput(),
      'value_type'          => new sfWidgetFormFilterInput(),
      'is_disp_regist'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_disp_config'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_disp_search'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'value_regexp'        => new sfWidgetFormFilterInput(),
      'value_min'           => new sfWidgetFormFilterInput(),
      'value_max'           => new sfWidgetFormFilterInput(),
      'sort_order'          => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                => new sfValidatorPass(array('required' => false)),
      'is_required'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_unique'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_edit_public_flag' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'default_public_flag' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'form_type'           => new sfValidatorPass(array('required' => false)),
      'value_type'          => new sfValidatorPass(array('required' => false)),
      'is_disp_regist'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_disp_config'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_disp_search'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'value_regexp'        => new sfValidatorPass(array('required' => false)),
      'value_min'           => new sfValidatorPass(array('required' => false)),
      'value_max'           => new sfValidatorPass(array('required' => false)),
      'sort_order'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'name'                => 'Text',
      'is_required'         => 'Boolean',
      'is_unique'           => 'Boolean',
      'is_edit_public_flag' => 'Boolean',
      'default_public_flag' => 'Number',
      'form_type'           => 'Text',
      'value_type'          => 'Text',
      'is_disp_regist'      => 'Boolean',
      'is_disp_config'      => 'Boolean',
      'is_disp_search'      => 'Boolean',
      'value_regexp'        => 'Text',
      'value_min'           => 'Text',
      'value_max'           => 'Text',
      'sort_order'          => 'Number',
    );
  }
}