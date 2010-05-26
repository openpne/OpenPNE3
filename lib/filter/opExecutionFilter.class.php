<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opExecutionFilter
 *
 * @package    OpenPNE
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opExecutionFilter extends sfExecutionFilter
{
  public static function notifyPreExecuteActionEvent($subject, sfEventDispatcher $dispatcher, sfAction $actionInstance)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();

    $params = array(
      'moduleName'     => $moduleName,
      'actionName'     => $actionName,
      'actionInstance' => $actionInstance,
    );

    $dispatcher->notify(new sfEvent($subject, 'op_action.pre_execute_'.$moduleName.'_'.$actionName, $params));
    $dispatcher->notify(new sfEvent($subject, 'op_action.pre_execute', $params));
  }

  public static function notifyPostExecuteActionEvent($subject, sfEventDispatcher $dispatcher, sfAction $actionInstance, $result)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();

    $params = array(
      'moduleName'     => $moduleName,
      'actionName'     => $actionName,
      'actionInstance' => $actionInstance,
      'result'         => $result,
    );

    $dispatcher->notify(new sfEvent($subject, 'op_action.post_execute_'.$moduleName.'_'.$actionName, $params));
    $dispatcher->notify(new sfEvent($subject, 'op_action.post_execute', $params));
  }

  protected function handleSSl($actionInstance)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();
    $request = $actionInstance->getRequest();

    $currentPath = $request->getCurrentUri();

    if (sfConfig::get('op_use_ssl', false) && $this->isFirstCall())
    {
      $sslRequiredAppList = sfConfig::get('op_ssl_required_applications', array());
      $sslRequiredList = sfConfig::get('op_ssl_required_actions', array(
        sfConfig::get('sf_app') => array(),
      ));
      $sslSelectableList = sfConfig::get('op_ssl_selectable_actions', array(
        sfConfig::get('sf_app') => array(),
      ));

      if (in_array(sfConfig::get('sf_app'), $sslRequiredAppList))
      {
        if (!$request->isSecure())
        {
          $baseUrl = sfConfig::get('op_ssl_base_url');

          $actionInstance->redirect($baseUrl[sfConfig::get('sf_app')].$currentPath);
        }
      }
      elseif (in_array($moduleName.'/'.$actionName, $sslRequiredList[sfConfig::get('sf_app')]))
      {
        if (!$request->isSecure())
        {
          $baseUrl = sfConfig::get('op_ssl_base_url');

          $actionInstance->redirect($baseUrl[sfConfig::get('sf_app')].$currentPath);
        }
      }
      elseif (!in_array($moduleName.'/'.$actionName, $sslSelectableList[sfConfig::get('sf_app')]) && $request->isSecure())
      {
        $baseUrl = sfConfig::get('op_base_url');

        $actionInstance->redirect($baseUrl.$currentPath);
      }
    }
  }

  protected function handleAction($filterChain, $actionInstance)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();
    $request = $actionInstance->getRequest();

    if ($request->needToRedirectToSoftBankGateway())
    {
      $request->redirectToSoftBankGateway();
    }

    $this->handleSSl($actionInstance);

    $dispatcher = sfContext::getInstance()->getEventDispatcher();

    // sfDoctrinePlugin needs to notify this event for enabling i18n
    $dispatcher->notify(new sfEvent(
      $this, 'user.change_culture', array('culture' => sfContext::getInstance()->getUser()->getCulture())
    ));

    self::notifyPreExecuteActionEvent($this, $dispatcher, $actionInstance);

    Doctrine::getTable('SnsTerm')->configure(sfContext::getInstance()->getUser()->getCulture(), sfConfig::get('sf_app'));

    if (sfConfig::has('op_is_use_captcha'))
    {
      sfConfig::set('op_is_use_captcha', opConfig::get('is_use_captcha'));
    }

    try
    {
      $result = parent::handleAction($filterChain, $actionInstance);
    }
    catch (opRuntimeException $e)
    {
      $this->forwardToErrorAction();
    }
    catch (sfValidatorErrorSchema $e)
    {
      if (isset($e['_csrf_token']))
      {
        $this->forwardToCSRFErrorAction();
      }

      throw $e;
    }

    self::notifyPostExecuteActionEvent($this, $dispatcher, $actionInstance, $result);

    return $result;
  }

  protected function forwardToErrorAction()
  {
    $this->context->getController()->forward('default', 'error');

    throw new sfStopException();
  }

  protected function forwardToCSRFErrorAction()
  {
    $this->context->getController()->forward('default', 'csrfError');

    throw new sfStopException();
  }
}
