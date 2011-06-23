<?php

class opLanguageSelecterForm extends BaseForm
{
  protected 
    $user = null;

  public function configure()
  {
    $this->user = sfContext::getInstance()->getUser();

    $languages = sfConfig::get('op_supported_languages');
    $opt_languages = $this->getOption('languages', array());

    $languages = array_unique(array_merge($languages, $opt_languages));

    $choices = opToolkit::getCultureChoices($languages);
    
    $this->setDefaults(array(
      'culture' => $this->user->getCulture()
    ));

    $this->setWidgets(array(
      'culture' => new sfWidgetFormChoice(array('choices' => $choices)),
      'next_uri' => new opWidgetFormInputHiddenNextUri(),
    ));   
    $this->setValidators(array(
      'culture' => new sfValidatorChoice(array('choices' => array_keys($choices))),
      'next_uri' => new opValidatorNextUri(),
    ));

    $this->widgetSchema->setLabels(array(
      'culture' => 'Languages',
    ));
    
    $this->widgetSchema->setNameFormat('language[%s]');
  }

  public function setCulture()
  {
    if (!$this->isValid())
    {
      throw $this->errorSchema;
    }
    
    $this->user->setCulture($this->getValue('culture'));
  }
  
  public function saveCulture()
  {
    if (!$this->isValid())
    {
      throw $this->errorSchema;
    }
    
    if ($this->user->getMemberId())
    {
      $this->user->getMember()->setConfig('language', $this->getValue('culture'));
    }
  }
  
  public function bindAndSetCulture($taintedValues)
  {
    $this->bind($taintedValues);
    if ($this->isValid())
    {
      $this->setCulture();
      $this->saveCulture();
      
      return true;
    }
    
    return false;
  }
}
