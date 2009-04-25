<?php

/**
 * Navigation form base class.
 *
 * @package    form
 * @subpackage navigation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseNavigationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'type'       => new sfWidgetFormInput(),
      'uri'        => new sfWidgetFormTextarea(),
      'sort_order' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'Navigation', 'column' => 'id', 'required' => false)),
      'type'       => new sfValidatorString(array('max_length' => 64)),
      'uri'        => new sfValidatorString(array('max_length' => 2147483647)),
      'sort_order' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('navigation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Navigation';
  }

}