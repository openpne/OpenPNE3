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
        if ($attribute instanceof sfForm)
        {
          $attribute->getWidgetSchema()->setFormFormatterName('mobile');
          $viewAttributes[$key] = $attribute;
        }
      }
    }

    parent::executeView($moduleName, $actionName, $viewName, $viewAttributes);
  }
}
