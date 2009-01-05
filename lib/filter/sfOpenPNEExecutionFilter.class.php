<?php

/**
 * sfOpenPNEExecutionFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEExecutionFilter extends sfExecutionFilter
{
  protected function executeView($moduleName, $actionName, $viewName, $viewAttributes)
  {
    if (sfConfig::get('app_is_mobile'))
    {
      foreach ($viewAttributes as $key => $attribute)
      {
        $this->setFormFormatterForMobile($attribute);
        $viewAttributes[$key] = $attribute;
      }
    }

    parent::executeView($moduleName, $actionName, $viewName, $viewAttributes);
  }

  protected function setFormFormatterForMobile(&$form)
  {
    if (is_array($form))
    {
      array_map(array($this, 'setFormFormatterForMobile'), $form);
    } elseif ($form instanceof sfForm)
    {
      $form->getWidgetSchema()->setFormFormatterName('mobile');
    }
  }
}
