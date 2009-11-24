<?php

/**
 * ProfileTranslation form.
 *
 * @package    form
 * @subpackage ProfileTranslation
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ProfileTranslationForm extends BaseProfileTranslationForm
{
  public function configure()
  {
    $this->setWidget('caption', new sfWidgetFormInputText());
    $this->setWidget('info', new sfWidgetFormInputText());
    $this->setValidator('caption', new opValidatorString(array('trim' => true)));
    $this->setValidator('info', new opValidatorString(array('trim' => true, 'required' => false)));
  }
}
