<?php

/**
 * This file is part of the sfSymfonyTemplatingViewPlugin package.
 * (c) Kousuke Ebihara (http://co3k.org/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once(dirname(__FILE__).'/../../bootstrap/config.php');

require_once(sfConfig::get('sf_symfony_lib_dir').'/../test/unit/sfContextMock.class.php');
require_once(sfConfig::get('sf_symfony_lib_dir').'/vendor/lime/lime.php');
require_once(dirname(__FILE__).'/../sfApplicationConfigurationMock.php');

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
}

sfConfig::set('app_sfSymfonyTemplatingViewPlugin_renderers', array('php' => 'sfTemplateRendererPhp'));

// --

$t->diag('rendering view for the module/action action');
$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'action', 'Success');
$view->execute();

$result = $view->render();
$tplDir = $context->configuration->getTemplateDir('module', 'action');

$t->ok(0 === strpos($result, $tplDir), 'Calculating template directory successfuly');
$t->is(str_replace($tplDir, '', $result), '/actionSuccess.php', 'Rendering a valid template file');

// --

$t->diag('rendering view for the module/action action as xml');
$context = createContext();
$context->getRequest()->setFormat('xml', 'application/xml');
$context->getRequest()->setRequestFormat('xml');
$view = new sfTemplatingComponentView($context, 'module', 'action', 'Success');
$view->execute();

$result = $view->render();
$tplDir = $context->configuration->getTemplateDir('module', 'action');

$t->ok(0 === strpos($result, $tplDir), 'Calculating template directory successfuly');
$t->is('/actionSuccess.xml.php', str_replace($tplDir, '', $result), 'Rendering a valid template file');

// --

$t->diag('rendering view for the module/withLayout action that its view is extending other template');
$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'withLayout', 'Success');
$view->execute();

$result = $view->render();

$t->is($result, 'layout.php', 'Rendering a valid template file');

// --

$t->diag('rendering view for the module/withLayout action as xml that its view is extending other template');
$context = createContext();
$context->getRequest()->setFormat('xml', 'application/xml');
$context->getRequest()->setRequestFormat('xml');
$view = new sfTemplatingComponentView($context, 'module', 'withLayout', 'Success');
$view->execute();

$result = $view->render();

$t->is($result, 'layout.xml.php', 'Rendering a valid template file');

// --

$t->diag('rendering view for the module/helper action that uses helper function');
$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'helper', 'Success');
$view->execute();

$result = $view->render();
$tplDir = $context->configuration->getTemplateDir('module', 'helper');

$t->is($result, '<a href="http://example.com/">http://example.com/</a>', 'Rendering a valid template file');
