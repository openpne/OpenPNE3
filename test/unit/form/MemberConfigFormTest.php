<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(33);

//------------------------------------------------------------

class dummyActions extends sfActions
{
}

opMailSend::initialize();
Zend_Mail::setDefaultTransport(new opZendMailTransportMock());

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
sfContext::createInstance($configuration);
sfContext::getInstance()->getController()->getActionStack()->addEntry('member', 'home', new dummyActions(sfContext::getInstance(), 'member', 'home'));

sfForm::disableCSRFProtection();
sfConfig::set('op_is_use_captcha', false);
$member = Doctrine::getTable('Member')->find(1);
$form = new MemberConfigMobileAddressForm($member, array(), false);

$form->bind(array(
  'mobile_address'         => 'example@docomo.ne.jp',
  'mobile_address_confirm' => 'example@docomo.ne.jp',
));

$t->ok($form->isValid(), 'MemberConfigMobileAddressForm accepts valid form parameter');
$t->ok($form->save(), 'MemberConfigMobileAddressForm is able to save configuration');
$t->ok($member->getConfig('mobile_address_pre'), 'the mobile_address_pre configuration has stored successfully');
$t->ok($member->getConfig('mobile_address_token'), 'the mobile_address_token configuration has stored successfully');

$t->diag('IsConfirm Test');

sfConfig::set('openpne_member_category', array('account' => array('email_address')));
sfConfig::set('openpne_member_config', array(
  'email_address' => array(
    'Name' => 'email_address',
    'FormType' => 'text',
    'ValueType' => 'email',
    'IsRegist' => true,
    'IsRequired' => true,
    'IsConfirm' => true,
    'IsTrim' => true, // validated (clean) value is not always equal to raw value
  ),
));

$form = new MemberConfigForm();

$form->bind(array('email_address' => 'hoge@example.com', 'email_address_confirm' => 'hoge@example.com'));
$t->ok($form->isValid(), 'valid');

$t->info('Compare to validated value instead of raw value');
$form->bind(array('email_address' => '  hoge@example.com  ', 'email_address_confirm' => 'hoge@example.com'));
$t->ok($form->isValid(), 'valid');

$t->info('ValueType validate error');
$form->bind(array('email_address' => '#####', 'email_address_confirm' => '#####'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');
$t->ok(!$form['email_address_confirm']->hasError(), 'email_address_confirm has no error');

$t->info('Confirm field error');
$form->bind(array('email_address' => 'hoge@example.com', 'email_address_confirm' => 'foo@example.com'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok(!$form['email_address']->hasError(), 'email_address has no error');
$t->ok($form['email_address_confirm']->hasError(), 'email_address_confirm has error');

$t->diag('IsUnique Test');

sfConfig::set('openpne_member_category', array('account' => array('email_address')));
sfConfig::set('openpne_member_config', array(
  'email_address' => array(
    'Name' => 'email_address',
    'FormType' => 'input',
    'ValueType' => 'email',
    'IsRegist' => true,
    'IsRequired' => true,
    'IsUnique' => true,
    'IsTrim' => true, // validated (clean) value is not always equal to raw value
  ),
));

$form = new MemberConfigForm();

$form->bind(array('email_address' => 'hoge@example.com'));
$t->ok($form->isValid(), 'valid');

$t->info('ValueType validate error');
$form->bind(array('email_address' => '#####'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');

$t->info('Unique constraint error');
Doctrine_Core::getTable('Member')->find(1)->setConfig('email_address', 'sns@example.com');
$form->bind(array('email_address' => 'sns@example.com'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');

$t->info('Unique constraint must be checked by validated value instead of raw value');
Doctrine_Core::getTable('Member')->find(1)->setConfig('email_address', 'sns@example.com');
$form->bind(array('email_address' => '  sns@example.com  '));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');

$t->diag('IsConfirm + IsUnique Test');

sfConfig::set('openpne_member_category', array('account' => array('email_address')));
sfConfig::set('openpne_member_config', array(
  'email_address' => array(
    'Name' => 'email_address',
    'FormType' => 'input',
    'ValueType' => 'email',
    'IsRegist' => true,
    'IsRequired' => true,
    'IsConfirm' => true,
    'IsUnique' => true,
    'IsTrim' => true, // validated (clean) value is not always equal to raw value
  ),
));

$form = new MemberConfigForm();

$form->bind(array('email_address' => 'hoge@example.com', 'email_address_confirm' => 'hoge@example.com'));
$t->ok($form->isValid(), 'valid');

$t->info('Compare to validated value instead of raw value');
$form->bind(array('email_address' => '  hoge@example.com  ', 'email_address_confirm' => 'hoge@example.com'));
$t->ok($form->isValid(), 'valid');

$t->info('ValueType validate error');
$form->bind(array('email_address' => '#####', 'email_address_confirm' => '#####'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');
$t->ok(!$form['email_address_confirm']->hasError(), 'email_address_confirm has no error');

$t->info('Confirm field error');
$form->bind(array('email_address' => 'hoge@example.com', 'email_address_confirm' => 'foo@example.com'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok(!$form['email_address']->hasError(), 'email_address has no error');
$t->ok($form['email_address_confirm']->hasError(), 'email_address_confirm has error');

$t->info('Unique constraint error');
Doctrine_Core::getTable('Member')->find(1)->setConfig('email_address', 'sns@example.com');
$form->bind(array('email_address' => 'sns@example.com', 'email_address_confirm' => 'sns@example.com'));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');
$t->ok(!$form['email_address_confirm']->hasError(), 'email_address_confirm has no error');

$t->info('Unique constraint must be checked by validated value instead of raw value');
Doctrine_Core::getTable('Member')->find(1)->setConfig('email_address', 'sns@example.com');
$form->bind(array('email_address' => '  sns@example.com  ', 'email_address_confirm' => '  sns@example.com  '));
$t->ok(!$form->isValid(), 'not valid');
$t->ok($form['email_address']->hasError(), 'email_address has error');
$t->ok(!$form['email_address_confirm']->hasError(), 'email_address_confirm has no error');
