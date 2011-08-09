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
  protected $retrivingMobileUIDActions = array('member/register', 'member/registerInput', 'member/registerEnd', 'member/configUID', 'member/registerMobileToRegisterEnd');
  protected $mobileUIDAuthModeName = 'MobileUID';

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

  protected function needToRetrieveMobileUID($moduleName, $actionName, $request, $sslSelectableList)
  {
    if ('mobile_frontend' !== sfConfig::get('sf_app'))
    {
      return false;
    }

    $action = $moduleName.'/'.$actionName;

    if (in_array($action, $sslSelectableList[sfConfig::get('sf_app')]))
    {
      if (in_array($action, $this->retrivingMobileUIDActions))
      {
        return true;
      }
      elseif ('member/login' === $action && $request->getParameter('authMode') === $this->mobileUIDAuthModeName)
      {
        return true;
      }
    }

    return false;
  }

  protected function handleSsl($actionInstance)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();
    $request = $actionInstance->getRequest();

    $scriptName = basename($request->getScriptName());
    $replacement = (false !== strpos($request->getPathInfoPrefix(), $scriptName)) ? '/'.$scriptName : '';
    $currentPath = str_replace($request->getPathInfoPrefix(), $replacement, $request->getCurrentUri());

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
      elseif ($this->needToRetrieveMobileUID($moduleName, $actionName, $request, $sslSelectableList) && $request->isSecure())
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

    $this->handleSsl($actionInstance);

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
    catch (Zend_Mail_Protocol_Exception $e)
    {
      if (sfConfig::get('sf_logging_enabled'))
      {
        $this->context->getLogger()->err('Mail Send Error: '.$e->getMessage());
      }

      $this->forwardToMailErrorAction();
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

  protected function forwardToMailErrorAction()
  {
    $this->context->getController()->forward('default', 'mailError');

    throw new sfStopException();
  }
}
