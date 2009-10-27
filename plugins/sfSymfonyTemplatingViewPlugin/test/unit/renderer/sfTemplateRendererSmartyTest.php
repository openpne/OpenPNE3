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

  class mySmarty extends Smarty
  {
  }

  class myRenderer extends sfTemplateRendererSmarty
  {
    public function getSmarty()
    {
      return $this->smarty;
    }

    public function myMkdir($dirname)
    {
      return $this->mkdir($dirname);
    }
  }

  class myStorage extends sfTemplateStorage
  {
  }
}

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/../../../lib/vendor/smarty2');

$t = new lime_test();

$t->diag('->__construct()');

$renderer = new myRenderer();
$t->is(get_class($renderer->getSmarty()), 'Smarty', '->__construct() creates an instance of "Smarty" automatically');

$renderer = new myRenderer(new mySmarty());
$t->is(get_class($renderer->getSmarty()), 'mySmarty', '->__construct() uses the specified instance of "mySmarty"');

$t->diag('->evaluate()');
$fixtureDir = realpath(dirname(__FILE__).'/../fixtures');

$smarty = new mySmarty();
$renderer = new myRenderer($smarty);

$smarty->compile_dir = '/tmp';
$smarty->cache_dir = '/tmp';

$t->is($renderer->evaluate(new sfTemplateStorageString('Kousuke')), 'Kousuke', '->evaluate() returns string from the sfTemplateStorageString');
$t->is($renderer->evaluate(new sfTemplateStorageFile($fixtureDir.'/template/smarty.tpl')), trim(file_get_contents($fixtureDir.'/template/smarty.tpl')), '->evaluate() returns string from the sfTemplateStorageFile');
$t->is($renderer->evaluate(new sfTemplateStorageString('{$name}'), array('name' => 'Ebihara')), 'Ebihara', '->evaluate() returns string from the sfTemplateStorageString and the parameters');
$t->is($renderer->evaluate(new myStorage('invalid')), false, '->evaluate() returns false if the specified storage is not valid');

$t->diag('->mkdir()');
$dirPath = '/tmp/'.md5(__FILE__.time());
$renderer->myMkdir($dirPath);
$t->is(is_writable($dirPath), true, '->mkdir() creates writable directory');
$t->is($renderer->myMkdir($dirPath), true, '->mkdir() returns true if the specified directory is exists');

