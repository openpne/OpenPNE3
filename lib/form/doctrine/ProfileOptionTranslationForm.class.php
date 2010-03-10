<?php

/**
 * ProfileOptionTranslation form.
 *
 * @package    form
 * @subpackage ProfileOptionTranslation
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileOptionTranslationForm extends BaseProfileOptionTranslationForm
{
  public function configure()
  {
    $this->setWidget('value', new sfWidgetFormInputText());
    $this->setValidator('value', new opValidatorString(array('trim' => true, 'required' => true)));
  }
}
