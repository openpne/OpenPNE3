<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('0. Login')
  ->get('/default/login')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))
  ->isStatusCode(302)

  ->info('1. When an admin user tries to change the SNS configuration. (ref. #3488)')
  ->info('A category is not selected, admin user can change the general configuration.')
  ->get('/sns/config')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('A general category is selected, admin user can change the general configuration.')
  ->get('/sns/config/category/general')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('An authentication category is selected, admin user can change the authentication configuration.')
  ->get('/sns/config/category/authentication')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('A mobile category is selected, admin user can change the mobile configuration.')
  ->get('/sns/config/category/mobile')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('A policy category is selected, admin user can change the policy configuration.')
  ->get('/sns/config/category/policy')
  ->click('設定変更')
  ->isStatusCode(302)

  ->info('/sns/config - CSRF')
  ->post('/sns/config', array())
  ->checkCSRF()

  ->info('/sns/config/category/external_login_page - CSRF')
  ->post('/sns/config/category/external_login_page', array())
  ->checkCSRF()

  ->info('/sns/config/category/authentication - CSRF')
  ->post('/sns/config/category/authentication', array())
  ->checkCSRF()

  ->info('/sns/config/category/mobile - CSRF')
  ->post('/sns/config/category/mobile', array())
  ->checkCSRF()

  ->info('/sns/config/category/policy - CSRF')
  ->post('/sns/config/category/policy', array())
  ->checkCSRF()

  ->info('/sns/config/category/api_keys - CSRF')
  ->post('/sns/config/category/api_keys', array())
  ->checkCSRF()

  ->info('/sns/term - CSRF')
  ->post('/sns/term', array())
  ->checkCSRF()

  ->info('/sns/cache - CSRF')
  ->post('/sns/cache', array())
  ->checkCSRF()

  ->info('/sns/richTextarea - CSRF')
  ->post('/sns/richTextarea', array())
  ->checkCSRF()
;
