<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

include_once dirname(__FILE__).'/../../bootstrap/unit.php';

class testContext extends sfContext
{
  public function getRouting()
  {
    return $this->routing;
  }

  public function getModuleName()
  {
    return 'module';
  }

  public function getActionName()
  {
    return 'action';
  }
}

class sfExecutionFilter extends sfFilter
{
  protected function handleAction($filterChain, $actionInstance)
  {
    return "action";
  }
}

class testRequest extends opWebRequest
{
  public $isRedirect = false;

  public function needToRedirectToSoftBankGateway()
  {
    return true;
  }

  public function redirectToSoftBankGateway()
  {
    $this->isRedirect = true;
    return;
  }

  public function getIsRedirect()
  {
    return $this->isRedirect;
  }
}

class testAction extends sfActions
{
  public $request = null;

  public function __construct($context, $moduleName, $actionName)
  {
    parent::__construct($context, $moduleName, $actionName);
    $this->request = new testRequest(new sfEventDispatcher(), null);
  }

  public function getRequest()
  {
    return $this->request;
  }
}

class myFilter extends opExecutionFilter
{
  public function handleAction($filterChain, $actionInstance)
  {
    $result = parent::handleAction($filterChain, $actionInstance);
    var_dump($actionInstance->getRequest()->getIsRedirect());
    if ($actionInstance->getRequest()->getIsRedirect())
    {
      return "redirect";
    }
    else
    {
      return $result;
    }
  }

  public function setTestDate($date_str)
  {
    $this->spec_change_date = $date_str;
  }
}

$t = new lime_test(2);

require_once dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
testContext::createInstance($configuration);
$context = testContext::getInstance();

$filterChain = new sfFilterChain();
$filter = new myFilter($context);

$t->diag('spec_change_date < test_date');
$filter->setTestDate(date('Y-m-d H:i:s', time() - 60));
$actionInstance = new testAction($context, $context->getModuleName(), $context->getActionName());
$t->is($filter->handleAction($filterChain, $actionInstance), "action", "after spec_change_date, don't redirect to SoftBank GW");

$t->diag('spec_change_date > test_date');
$filter->setTestDate(date('Y-m-d H:i:s', time() + 60));
$actionInstance = new testAction($context, $context->getModuleName(), $context->getActionName());
$t->is($filter->handleAction($filterChain, $actionInstance), "redirect", "before spec_change_date, redirect to SoftBank GW");