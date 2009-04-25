<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * GadgetConfig filter form base class.
 *
 * @package    filters
 * @subpackage GadgetConfig *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseGadgetConfigFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'      => new sfWidgetFormFilterInput(),
      'gadget_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Gadget', 'add_empty' => true)),
      'value'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'      => new sfValidatorPass(array('required' => false)),
      'gadget_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Gadget', 'column' => 'id')),
      'value'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gadget_config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GadgetConfig';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'name'      => 'Text',
      'gadget_id' => 'ForeignKey',
      'value'     => 'Text',
    );
  }
}