<?php

/**
 * Base project form.
 * 
 * @package    OpenPNE
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    parent::__construct($defaults, $options, $CSRFSecret);

    if ('mobile_frontend' === sfConfig::get('sf_app'))
    {
      $this->appendMobileInputMode();
    }
  }

  protected function appendMobileInputMode()
  {
    foreach ($this as $k => $v)
    {
      $widget = $this->widgetSchema[$k];
      $validator = $this->validatorSchema[$k];

      if (!($widget instanceof sfWidgetFormInput))
      {
        continue;
      }

      if ($widget instanceof sfWidgetFormInputPassword)
      {
        opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'alphabet');
      }
      elseif ($validator instanceof sfValidatorEmail)
      {
        opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'alphabet');
      }
      elseif ($validator instanceof sfValidatorNumber)
      {
        opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'numeric');
      }
    }
  }
}
