<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfOpenPNEExecutionFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfOpenPNEExecutionFilter extends sfExecutionFilter
{
  protected function handleAction($filterChain, $actionInstance)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();
    $dispatcher = sfContext::getInstance()->getEventDispatcher();

    $dispatcher->notify(new sfEvent($this, 'op_action.pre_execute_'.$moduleName.'_'.$actionName, array(
      'moduleName'     => $moduleName,
      'actionName'     => $actionName,
      'actionInstance' => $actionInstance,
    )));

    try
    {
      $result = parent::handleAction($filterChain, $actionInstance);
    }
    catch (opRuntimeException $e)
    {
      $this->forwardToErrorAction();
    }

    $dispatcher->notify(new sfEvent($this, 'op_action.post_execute_'.$moduleName.'_'.$actionName, array(
      'moduleName'     => $moduleName,
      'actionName'     => $actionName,
      'actionInstance' => $actionInstance,
      'result'         => $result,
    )));

    return $result;
  }

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
    }
    elseif ($form instanceof sfForm)
    {
      $form->getWidgetSchema()->setFormFormatterName('mobile');
    }
  }

  protected function forwardToErrorAction()
  {
    $this->context->getController()->forward('default', 'error');

    throw new sfStopException();
  }
}
