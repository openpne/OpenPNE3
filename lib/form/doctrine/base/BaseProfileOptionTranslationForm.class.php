<?php

/**
 * ProfileOptionTranslation form base class.
 *
 * @package    form
 * @subpackage profile_option_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseProfileOptionTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'value' => new sfWidgetFormTextarea(),
      'lang'  => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorDoctrineChoice(array('model' => 'ProfileOptionTranslation', 'column' => 'id', 'required' => false)),
      'value' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'lang'  => new sfValidatorDoctrineChoice(array('model' => 'ProfileOptionTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_option_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileOptionTranslation';
  }

}