<?php

require_once __DIR__.'/../../bootstrap/functional.php';

opMailSend::initialize();
Zend_Mail::setDefaultTransport(new opZendMailTransportMock());

Doctrine_Core::getTable('SnsConfig')->set('is_use_captcha', '0');

$member1 = Doctrine_Core::getTable('Member')->find(1);

$tester = new opTestFunctional(new opBrowser(), new lime_test(), array(
  'doctrine' => 'sfTesterDoctrine',
));

$tester->info('/member/config: Email Confirmation Test');

$tester
  ->login('sns@example.com', 'password')

  ->get('/member/config?category=pcAddress')
  ->click('送信', array(
    'member_config' => array(
      'pc_address' => 'sns+new@example.com',
      'pc_address_confirm' => 'sns+new@example.com',
    ),
  ))

  ->with('doctrine')->begin()
    ->check('MemberConfig', array('member_id' => 1, 'name' => 'pc_address', 'value' => 'sns@example.com'), 1)
    ->check('MemberConfig', array('member_id' => 1, 'name' => 'pc_address_pre', 'value' => 'sns+new@example.com'), 1)
    ->check('MemberConfig', array('member_id' => 1, 'name' => 'pc_address_token'), 1)
  ->end()
;

$confirmToken = $member1->getConfig('pc_address_token');

$tester
  ->get('/member/configComplete', array('id' => 1, 'type' => 'pc_address', 'token' => $confirmToken))
  ->click('送信', array(
    'password' => array(
      'password' => 'password',
    ),
  ))

  ->with('doctrine')->begin()
    ->check('MemberConfig', array('member_id' => 1, 'name' => 'pc_address', 'value' => 'sns+new@example.com'), 1)
    ->check('MemberConfig', array('member_id' => 1, 'name' => 'pc_address_pre'), false)
    ->check('MemberConfig', array('member_id' => 1, 'name' => 'pc_address_token'), false)
  ->end()
;
