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

  class myTemplate extends Doctrine_Record
  {
    public function __toString()
    {
      return $this->body;
    }

    public function setTableDefinition()
    {
      $this->setTableName('my_template');
      $this->hasColumn('id', 'integer', 4, array(
           'type' => 'integer',
           'primary' => true,
           'autoincrement' => true,
           'length' => '4',
           ));
      $this->hasColumn('name', 'string', 64, array(
           'type' => 'string',
           'default' => '',
           'notnull' => true,
           'length' => '64',
           ));
      $this->hasColumn('body', 'string', null, array(
           'type' => 'string',
           ));
      $this->hasColumn('renderer', 'string', 64, array(
           'type' => 'string',
           'default' => '',
           'notnull' => true,
           'length' => '64',
           ));
    }
  }

  class myQuery extends Doctrine_Query
  {
    public function fetchOne($params = array(), $hydrationMode = null)
    {
      $result = parent::fetchOne($params, $hydrationMode);

      $_p = $this->getParams($params);
      if ('exist' === $_p['where'][0])
      {
        $tpl = new myTemplate();
        $tpl->fromArray(array(
          'id'       => 1,
          'name'     => 'exist',
          'body'     => 'exist',
          'renderer' => 'php',
        ));

        return $tpl;
      }
      elseif ('empty' === $_p['where'][0])
      {
        $tpl = new myTemplate();
        $tpl->fromArray(array(
          'id'       => 1,
          'name'     => '',
          'body'     => '',
          'renderer' => 'php',
        ));

        return $tpl;
      }

      return $result;
    }
  }

  function initAdapter($adapter)
  {
    while ($adapter->pop());
  }

  function getConnection()
  {
    $adapter = new Doctrine_Adapter_Mock('mysql');
    $conn = Doctrine_Manager::connection($adapter, 'doctrine');
    $conn->setAttribute(Doctrine::ATTR_QUERY_CLASS, 'myQuery');

    return array($conn, $adapter);
  }
}

list ($conn, $adapter) = getConnection();
$record = new myTemplate();

$context = createContext();
$view = new sfTemplatingComponentView($context, 'module', 'action', '');
$loader = new sfTemplateSwitchableLoaderDoctrine($view, $context, array('model' => 'myTemplate'));

$t->diag('->load()');
$t->is((string)$loader->load('exist'), 'exist', '->load() returns template body if the specified template is found');
$t->is($adapter->pop(), 'SELECT m.id AS m__id, m.name AS m__name, m.body AS m__body, m.renderer AS m__renderer FROM my_template m WHERE (m.name = ? AND m.renderer = ?)', '->load() executes valid sql');
$t->cmp_ok($loader->load('unknown'), '===', false, '->load() returns empty string if the unknown template is specified');
$t->is($adapter->pop(), 'SELECT m.id AS m__id, m.name AS m__name, m.body AS m__body, m.renderer AS m__renderer FROM my_template m WHERE (m.name = ? AND m.renderer = ?)', '->load() executes valid sql');
$t->is($loader->load('exist', 'original')->getRenderer(), 'original', '->load() returns template that has specified renderer');
$t->cmp_ok($loader->load('empty'), '===', false, '->load() returns false if the specified template is empty string');

