<?php

class opLanguageSelecterForm extends BaseForm
{
  public function __construct($defaults = array(), $options = array())
  {
    parent::__construct($defaults, $options, false);

    $this->setWidget('next_uri', new opWidgetFormInputHiddenNextUri());
    $this->setValidator('next_uri', new opValidatorNextUri());
  }

  public function configure()
  {
    $user = sfContext::getInstance()->getUser();

    $languages = sfConfig::get('op_supported_languages');
    $opt_languages = $this->getOption('languages', array());

    $languages = array_unique(array_merge($languages, $opt_languages));

    $choices = opToolkit::getCultureChoices($languages);
    
    $this->setDefaults(array(
      'culture' => $user->getCulture()
    ));

    $this->setWidgets(array(
      'culture' => new sfWidgetFormChoice(array(
        'choices' => $choices
      )),
    ));
    
    $this->setValidators(array(
      'culture' => new sfValidatorChoice(array(
        'choices' => array_keys($choices)
      )),
    ));

    $this->widgetSchema->setLabels(array(
      'culture' => 'Languages',
    ));

    $this->widgetSchema->setNameFormat('language[%s]');
  }

  public function setCulture()
  {
    $user = sfContext::getInstance()->getUser();
    $user->setCulture($this->getValue('culture'));
  }
}
