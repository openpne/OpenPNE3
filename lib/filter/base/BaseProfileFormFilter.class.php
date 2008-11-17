<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Profile filter form base class.
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfPropelFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseProfileFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'           => new sfWidgetFormFilterInput(),
      'is_required'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_unique'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'form_type'      => new sfWidgetFormFilterInput(),
      'value_type'     => new sfWidgetFormFilterInput(),
      'value_regexp'   => new sfWidgetFormFilterInput(),
      'value_min'      => new sfWidgetFormFilterInput(),
      'value_max'      => new sfWidgetFormFilterInput(),
      'is_disp_regist' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_disp_config' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_disp_search' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sort_order'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'           => new sfValidatorPass(array('required' => false)),
      'is_required'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_unique'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'form_type'      => new sfValidatorPass(array('required' => false)),
      'value_type'     => new sfValidatorPass(array('required' => false)),
      'value_regexp'   => new sfValidatorPass(array('required' => false)),
      'value_min'      => new sfValidatorInteger(array('required' => false)),
      'value_max'      => new sfValidatorInteger(array('required' => false)),
      'is_disp_regist' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_disp_config' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_disp_search' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sort_order'     => new sfValidatorInteger(array('required' => false)),
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
      'id'             => 'Text',
      'name'           => 'Text',
      'is_required'    => 'Boolean',
      'is_unique'      => 'Boolean',
      'form_type'      => 'Text',
      'value_type'     => 'Text',
      'value_regexp'   => 'Text',
      'value_min'      => 'Text',
      'value_max'      => 'Text',
      'is_disp_regist' => 'Boolean',
      'is_disp_config' => 'Boolean',
      'is_disp_search' => 'Boolean',
      'sort_order'     => 'Text',
    );
  }
}
