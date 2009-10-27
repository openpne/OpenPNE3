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

  class myLoader extends sfTemplateSwitchableLoaderFilesystemForSymfony1
  {
    public function getTemplateDirs()
    {
      return $this->templateDirs;
    }
  }
}

$fixtureDir = realpath(dirname(__FILE__).'/../fixtures');

$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'action', '');
$loader = new myLoader($view, $context);

$loaderWithExt = new myLoader($view, $context, array('extension' => '.php'));
$loaderWithWrongExt = new myLoader($view, $context, array('extension' => '.unknown'));

$globalView = new sfTemplatingComponentView($context, 'global', '', '');
$globalLoader = new myLoader($globalView, $context);

$t->diag('->configure()');
$t->ok(in_array($fixtureDir.'/template/%name%.%extension%', $loader->getTemplateDirs()), '->configure() sets valid global template path');

$t->diag('->doLoad()');
$t->is((string)$loader->load('actionSuccess'), $fixtureDir.'/module/actionSuccess.php', '->doLoad() loads the specified local template');
$t->is((string)$loader->load('actionSuccess', 'php'), $fixtureDir.'/module/actionSuccess.php', '->doLoad() loads the specified local template with renderer');
$t->is((string)$loaderWithExt->load('actionSuccess'), $fixtureDir.'/module/actionSuccess.php', '->doLoad() loads the specified local template with extension');
$t->is($loader->load('unknownSuccess'), false, '->doLoad() returns false if the specified local template is not exists');
$t->is($loaderWithWrongExt->load('actionSuccess'), false, '->doLoad() returns false if the specified local template is exists but its extension is wrong');

$t->is((string)$globalLoader->load('layout'), $fixtureDir.'/template/layout.php', '->doLoad() loads the specified global template');

$t->is($loader->load('actionSuccess', 'original')->getRenderer(), 'original', '->load() returns template that has specified renderer');
