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
  ->click('設定変更', array('sns_config' => array(
    'sns_name'           => 'Super MySNS',
    'sns_title'          => 'Super MySNS - Come on join us! -',
    'admin_mail_address' => 'admin@example.com',
    'enable_pc'          => 1,
    'enable_mobile'      => 1,
  )))
  ->isStatusCode(302)

  ->info('A general category is selected, admin user can change the general configuration.')
  ->get('/sns/config/category/general')
  ->click('設定変更', array('sns_config' => array(
    'sns_name'           => 'Super MySNS',
    'sns_title'          => 'Super MySNS - Come on join us! -',
    'admin_mail_address' => 'admin@example.com',
    'enable_pc'          => 1,
    'enable_mobile'      => 1,
  )))
  ->isStatusCode(302)
;
