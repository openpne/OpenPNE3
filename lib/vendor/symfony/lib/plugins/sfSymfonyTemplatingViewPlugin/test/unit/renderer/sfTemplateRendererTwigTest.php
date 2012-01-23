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

if (!function_exists('createContext'))
{
  function createContext()
  {
    $context = sfContext::getInstance(array('request' => 'sfWebRequest', 'response' => 'sfWebResponse'), true);
    $context->configuration = new ApplicationConfigurationMock();
    sfConfig::set('sf_standard_helpers', array('Text'));

    return $context;
  }

  class myRenderer extends sfTemplateRendererTwig
  {
    public function getLoader()
    {
      return $this->loader;
    }

    public function getEnvironment()
    {
      return $this->environment;
    }
  }
}

$t = new lime_test();

$t->diag('->__construct()');

$renderer = new myRenderer();

$t->is(get_class($renderer->getLoader()), 'Twig_Loader_String', '->__construct() creates an instance of "Twig_Loader_String" automatically');
$t->is($renderer->getEnvironment()->getCharset(), 'UTF-8', '->__construct() creates an instance of "Twig_Environment" automatically');

$loader = new Twig_Loader_Filesystem('./');
$environment = new Twig_Environment($loader);
$environment->setCharset('Shift_JIS');
$renderer = new myRenderer($loader, $environment);

$t->is(get_class($renderer->getLoader()), 'Twig_Loader_Filesystem', '->__construct() uses the specified instance of "Twig_Loader_Filesystem"');
$t->is($renderer->getEnvironment()->getCharset(), 'Shift_JIS', '->__construct() uses the specified instance of "Twig_Environment"');


$t->diag('->evaluate()');
$fixtureDir = realpath(dirname(__FILE__).'/../fixtures');

$renderer = new sfTemplateRendererTwig();
$t->is($renderer->evaluate(new sfTemplateStorageString('Kousuke')), 'Kousuke', '->evaluate() returns string from the sfTemplateStorageString');
$t->is($renderer->evaluate(new sfTemplateStorageFile($fixtureDir.'/template/twig.tpl')), file_get_contents($fixtureDir.'/template/twig.tpl'), '->evaluate() returns string from the sfTemplateStorageFile');
$t->is($renderer->evaluate(new sfTemplateStorageString('{{ name }}'), array('name' => 'Ebihara')), 'Ebihara', '->evaluate() returns string from the sfTemplateStorageString and the parameters');

