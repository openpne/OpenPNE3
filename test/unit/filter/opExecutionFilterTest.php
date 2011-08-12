<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/../../bootstrap/unit.php';
require_once dirname(__FILE__).'/../../../lib/vendor/symfony/test/unit/sfContextMock.class.php';
require_once dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php';

$t = new lime_test();

class myFilter extends opExecutionFilter
{
  public function isFirstCall()
  {
    return true;
  }

  public function callNeedToRetrieveMobileUID($module, $action, $retrieveUIDMode = 1, $parameters = array())
  {
    opConfig::set('retrieve_uid', $retrieveUIDMode);

    return $this->needToRetrieveMobileUID($module, $action, new opWebRequest(new sfEventDispatcher(), $parameters), sfConfig::get('op_ssl_selectable_actions'));
  }

  public function createActionInstance($module, $action)
  {
    $className = 'TMP_'.md5(uniqid(true).$module.$action);
    eval('class '.$className.' extends sfAction{
      public $redirected = false;

      public function execute($request) {}
      public function initialize($context, $moduleName, $actionName)
      {
        $this->context = $context;
        $this->moduleName = $moduleName;
        $this->actionName = $actionName;
        $this->request = $context->getRequest();
      }

      public function redirect()
      {
        $this->redirected = true;
      }
    }');

    return new $className($this->context, $module, $action);
  }

  public function callHandleSsl($module, $action)
  {
    $instance = $this->createActionInstance($module, $action);
    $this->handleSsl($instance);

    return $instance->redirected;
  }
}

class myRequest extends opWebRequest
{
    public static $isSecure = false;

    public function isSecure()
    {
        return self::$isSecure;
    }
}

$context = sfContext::getInstance();
$context->configuration = ProjectConfiguration::getApplicationConfiguration('mobile_frontend', 'test', true);
$context->inject('request', 'myRequest');

$filter = new myFilter($context);

sfConfig::set('op_ssl_required_applications', array('secure_application'));
sfConfig::set('op_ssl_required_actions', array(
    'insecure_application' => array('secure/login', 'secure/logout'),
    'mobile_frontend' => array(),
));
sfConfig::set('op_ssl_selectable_actions', array(
  'insecure_application' => array('selectable/login'),
  'mobile_frontend' => array('member/register', 'member/registerInput', 'member/registerEnd', 'member/login', 'member/configUID'),
));

// ---

$t->diag('->needToRetrieveMobileUID()');
$t->ok($filter->callNeedToRetrieveMobileUID('member', 'configUID'), 'member/configUID redirects user to HTTP');
$t->ok($filter->callNeedToRetrieveMobileUID('member', 'configUID', 0), 'member/configUID does not redirects user to HTTP when it does not retrieve uid');
$t->ok(!$filter->callNeedToRetrieveMobileUID('member', 'home'), 'member/home does not redirect user to HTTP');
$t->ok(!$filter->callNeedToRetrieveMobileUID('member', 'login'), 'member/login does not redirect user to HTTP');
$t->ok($filter->callNeedToRetrieveMobileUID('member', 'login', 1, array('authMode' => 'MobileUID')), 'member/login redirect user to HTTP when the authMode is MobileUID');

$t->diag('->handleSsl()');

sfConfig::set('op_use_ssl', true);
myRequest::$isSecure = false;
sfConfig::set('sf_app', 'secure_application');
$t->ok($filter->callHandleSsl('anything', 'anything'), 'ssl-required-application redirects user HTTP to HTTPS');
sfConfig::set('sf_app', 'insecure_application');
$t->ok($filter->callHandleSsl('secure', 'login'), 'ssl-required-action redirects user HTTP to HTTPS');
$t->ok(!$filter->callHandleSsl('selectable', 'login'), 'ssl-selectable-action does not redirect user HTTP to HTTPS');
sfConfig::set('sf_app', 'mobile_frontend');
$t->ok(!$filter->callHandleSsl('member', 'configUID'), 'member/configUID does not redirect user HTTP to HTTPS');
$t->ok(!$filter->callHandleSsl('anything', 'anything'), 'no ssl providable action does not redirect user HTTP to HTTPS');

myRequest::$isSecure = true;
sfConfig::set('sf_app', 'secure_application');
$t->ok(!$filter->callHandleSsl('anything', 'anything'), 'ssl-required-application does not redirects user HTTPS to HTTP');
sfConfig::set('sf_app', 'insecure_application');
$t->ok(!$filter->callHandleSsl('secure', 'login'), 'ssl-required-action does not redirects user HTTPS to HTTP');
$t->ok(!$filter->callHandleSsl('selectable', 'login'), 'ssl-selectable-action does not redirect user HTTPS to HTTP');
sfConfig::set('sf_app', 'mobile_frontend');
$t->ok($filter->callHandleSsl('member', 'configUID'), 'member/configUID redirect user HTTPS to HTTP');
$t->ok($filter->callHandleSsl('anything', 'anything'), 'no ssl providable action redirect user HTTPS to HTTP');

sfConfig::set('op_use_ssl', false);
myRequest::$isSecure = false;
$t->ok(!$filter->callHandleSsl('anything', 'anything'), 'action does not redirect user HTTP to HTTPS if op_use_ssl is "false"');
myRequest::$isSecure = true;
$t->ok(!$filter->callHandleSsl('anything', 'anything'), 'action does not redirect user HTTPS to HTTP if op_use_ssl is "false"');
