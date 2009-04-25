<?php

/**
 * ProfileTranslation form base class.
 *
 * @package    form
 * @subpackage profile_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseProfileTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'caption' => new sfWidgetFormTextarea(),
      'info'    => new sfWidgetFormTextarea(),
      'lang'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => 'ProfileTranslation', 'column' => 'id', 'required' => false)),
      'caption' => new sfValidatorString(array('max_length' => 2147483647)),
      'info'    => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'lang'    => new sfValidatorDoctrineChoice(array('model' => 'ProfileTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('profile_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProfileTranslation';
  }

}