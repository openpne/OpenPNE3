<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

    $result = parent::handleAction($filterChain, $actionInstance);

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
}
