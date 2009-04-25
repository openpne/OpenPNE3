<?php

/**
 * NavigationTranslation form base class.
 *
 * @package    form
 * @subpackage navigation_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseNavigationTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'caption' => new sfWidgetFormTextarea(),
      'lang'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => 'NavigationTranslation', 'column' => 'id', 'required' => false)),
      'caption' => new sfValidatorString(array('max_length' => 2147483647)),
      'lang'    => new sfValidatorDoctrineChoice(array('model' => 'NavigationTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('navigation_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'NavigationTranslation';
  }

}