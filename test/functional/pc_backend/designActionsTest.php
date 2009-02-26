<?php

$executeLoader = false;
include(dirname(__FILE__).'/../../bootstrap/database.php');
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$params = array('admin_user' => array(
));
$browser->setTester('propel', 'sfTesterPropel');
$browser
  ->info('0. Login')
  ->get('/default/login')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))
  ->isStatusCode(302)

//---
  ->info('1. You can add and sort home gadget')
  ->get('/design/gadget/type/home')
  ->info('Add gadgets to all areas')
  ->click('設定変更', array('new' => array(
    'top' => array('memberImageBox'),
    'sideMenu' => array('memberImageBox'),
    'contents' => array('memberImageBox'),
    'bottom' => array('memberImageBox'),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('type' => 'top'), 3)
    ->check('Gadget', array('type' => 'sideMenu'), 4)
    ->check('Gadget', array('type' => 'contents'), 1)
    ->check('Gadget', array('type' => 'bottom'), 1)
  ->end()
  ->get('/design/gadget/type/home')
  ->info('Sort some gadgets')
  ->click('設定変更', array('gadget' => array(
    'top' => array(8, 2, 1),
    'sideMenu' => array(9, 4, 5, 3),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('id' => '8', 'sort_order' => 10), true)
    ->check('Gadget', array('id' => '2', 'sort_order' => 20), true)
    ->check('Gadget', array('id' => '1', 'sort_order' => 30), true)
    ->check('Gadget', array('id' => '9', 'sort_order' => 10), true)
    ->check('Gadget', array('id' => '4', 'sort_order' => 20), true)
    ->check('Gadget', array('id' => '5', 'sort_order' => 30), true)
    ->check('Gadget', array('id' => '3', 'sort_order' => 40), true)
  ->end()

//---
  ->info('2. You can add login gadget')
  ->get('/design/gadget/type/login')
  ->info('Add gadgets to all areas')
  ->click('設定変更', array('new' => array(
    'loginTop' => array('loginForm'),
    'loginSideMenu' => array('loginForm'),
    'loginContents' => array('loginForm'),
    'loginBottom' => array('loginForm'),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('type' => 'loginTop'), 1)
    ->check('Gadget', array('type' => 'loginSideMenu'), 1)
    ->check('Gadget', array('type' => 'loginContents'), 1)
    ->check('Gadget', array('type' => 'loginBottom'), 1)
  ->end()

//---
  ->info('3. You can add and sort side banner gadget')
  ->get('/design/gadget/type/sideBanner')
  ->info('Add gadgets to all areas')
  ->click('設定変更', array('new' => array(
    'sideBannerContents' => array('languageSelecterBox'),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('type' => 'sideBannerContents'), 2)
  ->end()
  ->get('/design/gadget/type/sideBanner')
  ->info('Sort some gadgets')
  ->click('設定変更', array('gadget' => array(
    'sideBannerContents' => array(16, 6),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('id' => '16', 'sort_order' => 10), true)
    ->check('Gadget', array('id' => '6', 'sort_order' => 20), true)
  ->end()

//---
  ->info('4. You can add and sort mobile home gadget')
  ->get('/design/gadget/type/mobileHome')
  ->info('Add gadgets to all areas')
  ->click('設定変更', array('new' => array(
    'mobileTop' => array('informationBox'),
    'mobileContents' => array('informationBox'),
    'mobileBottom' => array('informationBox'),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('type' => 'mobileTop'), 2)
    ->check('Gadget', array('type' => 'mobileContents'), 1)
    ->check('Gadget', array('type' => 'mobileBottom'), 1)
  ->end()
  ->get('/design/gadget/type/mobileHome')
  ->info('Sort some gadgets')
  ->click('設定変更', array('gadget' => array(
    'mobileTop' => array(17, 7),
  )))
  ->with('propel')->begin()
    ->check('Gadget', array('id' => '17', 'sort_order' => 10), true)
    ->check('Gadget', array('id' => '7', 'sort_order' => 20), true)
  ->end()

;
