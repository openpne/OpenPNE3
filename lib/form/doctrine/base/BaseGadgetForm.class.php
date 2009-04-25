<?php

/**
 * Gadget form base class.
 *
 * @package    form
 * @subpackage gadget
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseGadgetForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'type'       => new sfWidgetFormInput(),
      'name'       => new sfWidgetFormInput(),
      'sort_order' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'Gadget', 'column' => 'id', 'required' => false)),
      'type'       => new sfValidatorString(array('max_length' => 64)),
      'name'       => new sfValidatorString(array('max_length' => 64)),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gadget[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gadget';
  }

}