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

  protected function handleAction($filterChain, $actionInstance)
  {
    $moduleName = $actionInstance->getModuleName();
    $actionName = $actionInstance->getActionName();
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
