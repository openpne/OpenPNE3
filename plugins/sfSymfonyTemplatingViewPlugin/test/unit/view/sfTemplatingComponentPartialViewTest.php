<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/config.php');

require_once(sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php');
require_once(dirname(__FILE__).'/../sfContextMock.class.php');
require_once(dirname(__FILE__).'/../sfApplicationConfigurationMock.php');

$fixtureDir = realpath(dirname(__FILE__).'/../fixtures');

$t = new lime_test();

if (!function_exists('createContext'))
{
  function createContext()
  {
    $context = sfContext::getInstance(array('request' => 'sfWebRequest', 'response' => 'sfWebResponse'), true);
    $context->configuration = new ApplicationConfigurationMock();
    sfConfig::set('sf_standard_helpers', array('Text'));

    return $context;
  }

  function includeDummyPartial($templateName, $vars = array(), $context = null)
  {
    if (is_null($context))
    {
      $context = createContext();
    }

    if (false !== $sep = strpos($templateName, '/'))
    {
      $moduleName   = substr($templateName, 0, $sep);
      $templateName = substr($templateName, $sep + 1);
    }
    else
    {
      $moduleName = 'module'; // current module name
    }
    $actionName = '_'.$templateName;

    $view = new sfTemplatingComponentPartialView($context, $moduleName, $actionName, '');
    $view->setPartialVars($vars);

    try
    {
      return $view->render();
    }
    catch (Exception $e)
    {
      return false;
    }
  }

  function setLogMessageToGlobal($event)
  {
    $GLOBALS['sf_templating_log'] = $event[0];
  }
}

sfConfig::set('app_sfSymfonyTemplatingViewPlugin_renderers', array('php' => 'sfTemplateRendererPhp'));
sfConfig::set('sf_logging_enabled', true);

$t->diag('rendering partial');
$t->is(includeDummyPartial('ebi'), $fixtureDir.'/module/_ebi.php', 'Rendering a valid template file');
$t->is(includeDummyPartial('module/ebi'), $fixtureDir.'/module/_ebi.php', 'Rendering a valid template file (module specified)');
$t->is(includeDummyPartial('global/ebi'), $fixtureDir.'/template/_ebi.php', 'Rendering a valid global template file');
$basetime = time();
$t->is(includeDummyPartial('params', array('time' => $basetime)), $basetime, 'The template can accept variables');
$t->is(includeDummyPartial('helper'), '<a href="http://example.com/">http://example.com/</a>', 'The template can use helper function');

$t->diag('rendering partial as xml');
$context = createContext();
$context->getRequest()->setFormat('xml', 'application/xml');
$context->getRequest()->setRequestFormat('xml');
$t->is(includeDummyPartial('ebi', array(), $context), $fixtureDir.'/module/_ebi.xml.php', 'Rendering a valid template file');
$t->is(includeDummyPartial('module/ebi', array(), $context), $fixtureDir.'/module/_ebi.xml.php', 'Rendering a valid template file (module specified)');
$t->is(includeDummyPartial('global/ebi', array(), $context), $fixtureDir.'/template/_ebi.xml.php', 'Rendering a valid global template file');
$basetime = time();
$t->is(includeDummyPartial('params', array('time' => $basetime), $context), $basetime, 'The template can accept variables');
$t->is(includeDummyPartial('helper', array(), $context), '<a href="http://example.com/">http://example.com/</a>', 'The template can use helper function');

$t->diag('cache handling');
$context = createContext();
$view = new sfTemplatingComponentPartialView($context, 'module', 'ebi', '');
$t->is($view->getCache(), null, 'Cache is disabled');

$t->diag('logging event');
$GLOBALS['sf_templating_log'] = '';
$context = createContext();
$context->getEventDispatcher()->connect('application.log', 'setLogMessageToGlobal');
includeDummyPartial('ebi', array(), $context);
$t->ok($GLOBALS['sf_templating_log'] !== '', '"application.log" notify event is executed');
