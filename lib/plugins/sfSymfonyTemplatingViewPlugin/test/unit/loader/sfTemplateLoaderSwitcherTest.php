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

  class myLoaderSwitcher extends sfTemplateLoaderSwitcher
  {
    public function hasRule($key)
    {
      return isset($this->loaders[$key]);
    }

    public function getLoader($key, $index)
    {
      return $this->loaders[$key][$index];
    }

    public function countLoader($key)
    {
      return count($this->loaders[$key]);
    }
  }

  class myLoader extends sfTemplateAbstractSwitchableLoader
  {
    public function doLoad($template, $renderer = 'php')
    {
      if ('exist' === $template)
      {
        return $renderer;
      }
      elseif ($template === $renderer)
      {
        return $renderer;
      }
    }

    public function getRenderer()
    {
      return $this->getParameter('renderer');
    }
  }

  class myInvalidLoader extends sfTemplateLoader
  {
    public function load($template, $renderer = 'php')
    {
    }
  }
}

$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'action', '');

$rules = array(
  'php'   => array(
    array('loader' => 'myLoader', 'renderer' => 'php'),
  ),
  'user'  => array(
    array('loader' => 'myLoader', 'renderer' => 'smarty'),
    array('loader' => 'myLoader', 'renderer' => 'php'),
  ),
  'admin' => array(
    array('loader' => 'myLoader', 'renderer' => 'twig'),
    array('loader' => 'myLoader', 'renderer' => 'php'),
  ),
  'user_admin' => array(
    array('loader' => 'myLoader', 'renderer' => 'twig'),
    array('loader' => 'myLoader', 'renderer' => 'smarty'),
    array('loader' => 'myLoader', 'renderer' => 'php'),
  ),
  'customize' => array(
    array('loader' => 'myLoader', 'renderer' => 'original'),
  ),
);

$t->diag('->__construct()');
try
{
  $loader = new myLoaderSwitcher(array(), $view, $context);
}
catch (LogicException $e)
{
  $t->is($e->getMessage(), 'The "php" rule must be defined.', '->__construct() requires "php" rule');
}

try
{
  $loader = new myLoaderSwitcher(array('php' => array('loader' => 'myInvalidLoader')), $view, $context);
}
catch (LogicException $e)
{
  $t->is($e->getMessage(), 'The specified loader is invalid.', '->__construct() requires valid loader');
}

$loader = new myLoaderSwitcher($rules, $view, $context);
$t->diag('->__construct() sets specified rules');
$t->is($loader->hasRule('php'), true, 'loader has "php" rule');
$t->is($loader->hasRule('user'), true, 'loader has "user" rule');
$t->is($loader->hasRule('admin'), true, 'loader has "admin" rule');
$t->is($loader->hasRule('user_admin'), true, 'loader has "user_admin" rule');
$t->is($loader->hasRule('customize'), true, 'loader has "customize" rule');
$t->is($loader->hasRule('undefined'), false, 'loader doesn\'t have "undefined" rule');

$t->diag('"php" rule loaders');
$t->ok($loader->countLoader('php') === 1, '"php" rule has a loader');
$t->is($loader->getLoader('php', 0)->getRenderer(), 'php', 'A render of the first loader is "php"');

$t->diag('"user" rule loaders');
$t->ok($loader->countLoader('user') === 2, '"user" rule has 2 loaders');
$t->is($loader->getLoader('user', 0)->getRenderer(), 'smarty', 'The first render is "smarty"');
$t->is($loader->getLoader('user', 1)->getRenderer(), 'php', 'The second render is "php"');

$t->diag('"admin" rule loaders');
$t->ok($loader->countLoader('admin') === 2, '"admin" rule has 2 loaders');
$t->is($loader->getLoader('admin', 0)->getRenderer(), 'twig', 'The first render is "twig"');
$t->is($loader->getLoader('admin', 1)->getRenderer(), 'php', 'The second render is "php"');

$t->diag('"user_admin" rule loaders');
$t->ok($loader->countLoader('user_admin') === 3, '"user_admin" rule has 3 loaders');
$t->is($loader->getLoader('user_admin', 0)->getRenderer(), 'twig', 'The first render is "twig"');
$t->is($loader->getLoader('user_admin', 1)->getRenderer(), 'smarty', 'The first render is "smarty"');
$t->is($loader->getLoader('user_admin', 2)->getRenderer(), 'php', 'The second render is "php"');

$t->diag('"customize" rule loaders');
$t->ok($loader->countLoader('customize') === 1, '"customize" rule has a loader');
$t->is($loader->getLoader('customize', 0)->getRenderer(), 'original', 'The first render is "original"');

$t->diag('->load() includes exist template');
$t->is($loader->load('exist', 'php'), 'php', '"php" rule has "exist" template');
$t->is($loader->load('exist', 'user'),'smarty', '"user" rule has "exist" template as "smarty"');
$t->is($loader->load('exist', 'admin'), 'twig', '"admin" rule has "exist" template as "twig"');
$t->is($loader->load('exist', 'user_admin'), 'twig', '"user_admin" rule has "exist" template as "twig"');
$t->is($loader->load('exist', 'customize'), 'original', '"customize" rule has "exist" template as "original"');

$t->diag('->load() includes php template');
$t->is($loader->load('php', 'php'), 'php', '"php" rule has "php" template');
$t->is($loader->load('php', 'user'),'php', '"user" rule has "php" template as "php"');
$t->is($loader->load('php', 'admin'), 'php', '"admin" rule has "php" template as "php"');
$t->is($loader->load('php', 'user_admin'), 'php', '"user_admin" rule has "php" template as "php"');
$t->is($loader->load('php', 'customize'), false, '"customize" doesn\'t have "php" template');

$t->diag('->load() includes smarty template');
$t->is($loader->load('smarty', 'php'), false, '"php" rule doesn\'t have "smarty" template');
$t->is($loader->load('smarty', 'user'),'smarty', '"user" rule has "smarty" template as "smarty"');
$t->is($loader->load('smarty', 'admin'), false, '"admin" rule doesn\'t have "smarty" template');
$t->is($loader->load('smarty', 'user_admin'), 'smarty', '"user_admin" rule has "smarty" template as "smarty"');
$t->is($loader->load('smarty', 'customize'), false, '"customize" doesn\'t have "smarty" template');

$t->diag('->load() includes twig template');
$t->is($loader->load('twig', 'php'), false, '"php" rule doesn\'t have "twig" template');
$t->is($loader->load('twig', 'user'), false, '"user" rule doesn\'t have "twig" template');
$t->is($loader->load('twig', 'admin'), 'twig', '"admin" rule has "twig" template');
$t->is($loader->load('twig', 'user_admin'), 'twig', '"user_admin" rule has "twig" template');
$t->is($loader->load('twig', 'customize'), false, '"customize" doesn\'t have "twig" template');

$t->diag('->load() includes original template');
$t->is($loader->load('original', 'php'), false, '"php" rule doesn\'t have "original" template');
$t->is($loader->load('original', 'user'), false, '"user" rule doesn\'t have "original" template');
$t->is($loader->load('original', 'admin'), false, '"admin" rule doesn\'t have "original" template');
$t->is($loader->load('original', 'user_admin'), false, '"user_admin" rule doesn\'t have "original" template');
$t->is($loader->load('original', 'customize'), 'original', '"customize" rule has "original" template as "original"');

$t->diag('->load() includes unknown template');
$t->is($loader->load('unknown', 'php'), false, '"php" rule doesn\'t have "unknown" template');
$t->is($loader->load('unknown', 'user'), false, '"user" rule doesn\'t have "unknown" template');
$t->is($loader->load('unknown', 'admin'), false, '"admin" rule doesn\'t have "unknown" template');
$t->is($loader->load('unknown', 'user_admin'), false, '"user_admin" rule doesn\'t have "unknown" template');
$t->is($loader->load('unknown', 'customize'), false, '"customize" rule doesn\'t have "unknown" template');

$t->diag('->load() checks the specified renderer');
$t->is($loader->load('exist'), 'php', '->load() uses the "php" renderer if the second argument is empty');
try
{
  $loader->load('exist', 'unknown');
}
catch (LogicException $e)
{
  $t->is($e->getMessage(), 'The specified loader name "unknown" is not defined.', '->load() requires valid renderer');
}
