<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(11, new lime_output_color());

$pcAddressConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('pc_address', 1, true);
$passwordConfig  = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('password', 1, true);
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
function createModel($memberId, $name, $value = null)
{
  $memberConfig = new MemberConfig();
  $memberConfig->setMemberId($memberId);
  $memberConfig->setName($name);
  $memberConfig->setValue($value);

  return $memberConfig;
}

//------------------------------------------------------------
$t->diag('MemberConfig');
$t->diag('MemberConfig::savePre()');
$memberConfig = createModel(1, 'test1', 1);
$memberConfig->savePre();
$result = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('test1_pre', 1, true);
$t->isa_ok($result, 'MemberConfig');

//------------------------------------------------------------
$t->diag('MemberConfig::getValue()');
$memberConfig = createModel(1, 'test2', 1);
$t->is($memberConfig->getValue(), 1);
$memberConfig = createModel(1, 'test3', '1989-01-08');
$t->is($memberConfig->getValue(), '1989-01-08');

//------------------------------------------------------------
$t->diag('MemberConfig::getFormType()');
$t->is($pcAddressConfig->getFormType(), 'input');
$t->is($passwordConfig->getFormType(), 'password');

$memberConfig = createModel(1, 'test4', 1);
$t->is($pcAddressConfig->getFormType(), 'input');

//------------------------------------------------------------
$t->diag('MemberConfig::saveToken()');
$memberConfig = createModel(1, 'test5', 1);
$memberConfig->savePre();
$result1 = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('test5_pre', 1, true);
$result1->saveToken();

$result2 = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('test5_token', 1, true);
$t->isa_ok($result2, 'MemberConfig');

$memberConfig = createModel(1, 'test6', 1);
$memberConfig->saveToken();
$result3 = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('test6_token', 1, true);
$t->isa_ok($result3, 'MemberConfig');

//------------------------------------------------------------
$t->diag('MemberConfig::generateRoleId()');
$t->is($pcAddressConfig->generateRoleId($member1), 'self');
$t->is($pcAddressConfig->generateRoleId($member2), 'everyone');

//------------------------------------------------------------

$t->diag('MemberConfig::getNameValueHash()');
$memberConfig = createModel(1, 'test7', 'test7');
$memberConfig->save();
$t->is($memberConfig->getNameValueHash(), '767f31fc467e7879989b52aa54d8f60d', 'The "member_config.name_value_hash" is calculated by the given "test7,test7" string');
