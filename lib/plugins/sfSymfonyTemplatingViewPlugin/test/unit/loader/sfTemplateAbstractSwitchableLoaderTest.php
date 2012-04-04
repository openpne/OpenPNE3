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

  class myLoader extends sfTemplateAbstractSwitchableLoader
  {
    public $loaded = false;
    public $configured = false;

    public function configure()
    {
      $this->configured = true;
    }

    public function doLoad($template, $renderer = 'php')
    {
      $this->loaded = true;

      if ('invalidBool' === $template)
      {
        return false;
      }
      elseif ('invalidInt' === $template)
      {
        return 0;
      }
      elseif ('invalidString' === $template)
      {
        return '';
      }

      return 'valid';
    }
  }
}

$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'action', '');
$loader = new myLoader($view, $context, array('key' => 'value'));

$t->diag('->configure()');
$t->is($loader->configured, true, '->configure() is executed in the constructor');

$t->diag('->getParameter()');
$t->is($loader->getParameter('key'), 'value', '->getParameter() returns the value of the specified parameter');
$t->is($loader->getParameter('unknown-key'), null, '->getParameter() returns null if the specified parameter does not exist');
$t->is($loader->getParameter('unknown-key', 'default'), 'default', '->getParameter() returns the specified default value');

$t->diag('->doLoad()');
$t->is($loader->loaded, false, '->doLoad() is not executed in the constructor');
$loader->load('test');
$t->is($loader->loaded, true, '->doLoad() is executed in the load() method');
$t->ok($loader->load('invalidBool') === false, '->laod() returns false if the doLoad() method returns false');
$t->ok($loader->load('invalidInt') === false, '->laod() returns false if the doLoad() method returns 0');
$t->ok($loader->load('invalidString') === false, '->laod() returns false if the doLoad() method returns empty string');
$t->ok($loader->load('valid') === 'valid', '->laod() returns true if the doLoad() method returns string');

