<?php

// Test for double form submission in member registration process
// (Simulate IE/Chrome/Others)

$app = 'pc_frontend';
require_once __DIR__.'/../../../bootstrap/functional.php';

$test = new opTestFunctional(new opBrowser());

// Prevent sending mail
opMailSend::initialize();
Zend_Mail::setDefaultTransport(new opZendMailTransportMock());

// Disable all CAPTCHA for testing
Doctrine_Core::getTable('SnsConfig')->set('is_use_captcha', 0);

$test
  ->info('Registration request')
  ->login('sns@example.com', 'password')
  ->get('/member/invite')
  ->click('送信', array(
    'member_config' => array(
      'mail_address' => 'test01@example.com',
    ),
  ))
  ->get('/member/logout');

// Retrieve generated register_token from database
opActivateBehavior::disable();
$member = Doctrine_Core::getTable('MemberConfig')
  ->retrieveByNameAndValue('pc_address_pre', 'test01@example.com')->Member;
$registerToken = $member->getConfig('register_token');
opActivateBehavior::enable();

// Restart browser
$test->restart();

$test
  ->info('Registration process')

  ->get('/member/register', array('token' => $registerToken))
  ->checkDispatch('member', 'register')

  ->click('プロフィール入力ページへ')
  ->checkDispatch('member', 'registerInput')

  ->post('/member/registerInput/token/'.$registerToken, array(
    'member' => array('name' => 'test01'),
    'profile' => array(
      'op_preset_sex' => array('value' => 'Man'),
    ),
    'member_config' => array(
      'age_public_flag' => 1,
      'password' => 'password',
      'password_confirm' => 'password',
      'secret_question' => 1,
      'secret_answer' => 'hogehoge',
    ),
  ))
  ->checkDispatch('member', 'registerInput')

  ->info('Duplicate request (ex. Double form submission)')
  ->post('/member/registerInput/token/'.$registerToken, array(
    'member' => array('name' => 'test01'),
    'profile' => array(
      'op_preset_sex' => array('value' => 'Man'),
    ),
    'member_config' => array(
      'age_public_flag' => 1,
      'password' => 'password',
      'password_confirm' => 'password',
      'secret_question' => 1,
      'secret_answer' => 'hogehoge',
    ),
  ))
  ->checkDispatch('member', 'registerInput')

  ->followRedirect()
  ->checkDispatch('opAuthMailAddress', 'registerEnd')

  // Registration complete
  ->followRedirect()
  ->checkDispatch('member', 'home');
