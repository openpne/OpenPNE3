<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());

//------------------------------------------------------------

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
sfContext::createInstance($configuration);

$member = MemberPeer::retrieveByPk(1);
$form = new MemberConfigMobileAddressForm($member, array(), false);

$form->bind(array(
  'mobile_address'         => 'example@docomo.ne.jp',
  'mobile_address_confirm' => 'example@docomo.ne.jp',
));

$t->ok($form->isValid(), 'MemberConfigMobileAddressForm accepts valid form parameter');
$t->ok($form->save(), 'MemberConfigMobileAddressForm is able to save configuration');
$t->ok($member->getConfig('mobile_address_pre'), 'the mobile_address_pre configuration has stored successfully');
$t->ok($member->getConfig('mobile_address_token'), 'the mobile_address_token configuration has stored successfully');
