<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser->setTester('doctrine', 'sfTesterDoctrine');
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
  ->get('/design/gadget/type/gadget')
  ->info('Add gadgets to all areas')
  ->click('設定変更', array('new' => array(
    'top' => array('memberImageBox'),
    'sideMenu' => array('memberImageBox'),
    'contents' => array('memberImageBox'),
    'bottom' => array('memberImageBox'),
  )))
  ->with('doctrine')->begin()
    ->check('Gadget', array('type' => 'top'), 2)
    ->check('Gadget', array('type' => 'bottom'), 1)
  ->end()
  ->todo('Gadget (type = sideMenu)')
  ->todo('Gadget (type = contents)')
  ->get('/design/gadget/type/gadget')
  ->info('Sort some gadgets')
  ->click('設定変更', array('gadget' => array(
    'top' => array(10, 1),
    'sideMenu' => array(12, 3, 2),
  )))
  ->with('doctrine')->begin()
    ->check('Gadget', array('id' => '10', 'sort_order' => 10), true)
    ->check('Gadget', array('id' => '1', 'sort_order' => 20), true)
    ->check('Gadget', array('id' => '12', 'sort_order' => 10), true)
    ->check('Gadget', array('id' => '3', 'sort_order' => 20), true)
    ->check('Gadget', array('id' => '2', 'sort_order' => 30), true)
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
  ->with('doctrine')->begin()
    ->check('Gadget', array('type' => 'loginTop'), 2)
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
  ->with('doctrine')->begin()
    ->check('Gadget', array('type' => 'sideBannerContents'), 2)
  ->end()
  ->get('/design/gadget/type/sideBanner')
  ->info('Sort some gadgets')
  ->click('設定変更', array('gadget' => array(
    'sideBannerContents' => array(19, 4),
  )))
  ->with('doctrine')->begin()
  ->end()
  ->todo('Gadget (id = 19, sort_order = 10)')
  ->todo('Gadget (id = 4, sort_order = 20)')

//---
  ->info('4. You can add and sort mobile home gadget')
  ->get('/design/gadget/type/mobile')
  ->info('Add gadgets to all areas')
  ->click('設定変更', array('new' => array(
    'mobileTop' => array('informationBox'),
    'mobileContents' => array('informationBox'),
    'mobileBottom' => array('informationBox'),
  )))
  ->with('doctrine')->begin()
    ->check('Gadget', array('type' => 'mobileTop'), 2)
    ->check('Gadget', array('type' => 'mobileContents'), 1)
    ->check('Gadget', array('type' => 'mobileBottom'), 1)
  ->end()
  ->get('/design/gadget/type/mobile')
  ->info('Sort some gadgets')
  ->click('設定変更', array('gadget' => array(
    'mobileTop' => array(20, 5),
  )))
  ->with('doctrine')->begin()
    ->check('Gadget', array('id' => '20', 'sort_order' => 10), true)
  ->end()
  ->todo('Gadget (id = 5, sort_order = 20)')

// CSRF
  ->info('5. CSRF check')

  ->info('/design')
  ->post('/design')
  ->todo('checkCSRF')

  ->info('/design/gadget')
  ->post('/design/gadget')
  ->todo('checkCSRF')

  ->info('/design/editGadget/id/1')
  ->post('/design/editGadget/id/1')
  ->checkCSRF()

  ->info('/design/html')
  ->post('/design/html')
  ->checkCSRF()

  ->info('/design/banner')
  ->todo('checkCSRF')

  ->info('/design/banneradd')
  ->todo('checkCSRF')

  ->info('/design/customCss')
  ->post('/design/customCss')
  ->todo('checkCSRF')

  ->info('/design/mobileColorConfig')
  ->post('/design/mobileColorConfig')
  ->checkCSRF()
;
