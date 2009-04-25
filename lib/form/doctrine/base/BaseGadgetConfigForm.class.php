<?php

/**
 * GadgetConfig form base class.
 *
 * @package    form
 * @subpackage gadget_config
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseGadgetConfigForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'name'      => new sfWidgetFormInput(),
      'gadget_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Gadget', 'add_empty' => true)),
      'value'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorDoctrineChoice(array('model' => 'GadgetConfig', 'column' => 'id', 'required' => false)),
      'name'      => new sfValidatorString(array('max_length' => 64)),
      'gadget_id' => new sfValidatorDoctrineChoice(array('model' => 'Gadget', 'required' => false)),
      'value'     => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gadget_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GadgetConfig';
  }

}