<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$params = array('admin_user' => array(
));
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
;
