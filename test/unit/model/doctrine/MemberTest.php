<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(56, new lime_output_color());
$table = Doctrine::getTable('Member');
$member1 = $table->findOneByName('A');
$member2 = $table->findOneByName('B');
$member3 = $table->findOneByName('C');
$member4 = $table->findOneByName('D');
$member5 = $table->findOneByName('E');

//------------------------------------------------------------
$t->diag('Member');
$t->diag('Member::getProfiles()');
$result = $member1->getProfiles();
$t->isa_ok($result, 'array', '->getProfiles() returns array');
$t->is(count($result), 9, '->getProfiles() on the member1 returns 9 results');

$result = $member1->getProfiles(true, $member2->getId());
$t->isa_ok($result, 'array', '->getProfiles() returns array');
$t->is(count($result), 8, '->getProfiles() on the member1 returns 8 results to member2');

$result = $member1->getProfiles(true, $member3->getId());
$t->isa_ok($result, 'array', '->getProfiles() returns array');
$t->is(count($result), 7, '->getProfiles() on the member1 returns 7 results to member3');

$result = $member1->getProfiles(true, 0);
$t->isa_ok($result, 'array', '->getProfiles() returns array');
$t->is(count($result), 1, '->getProfiles() on the member1 returns 1 result to anonymous member');

$result = $member2->getProfiles(true, 0);
$t->isa_ok($result, 'array', '->getProfiles() retruns array');
$t->todo('->getProfiles() on the member1 returns no results to anonymous member');

//------------------------------------------------------------
$t->diag('Member::getProfile()');
$t->isa_ok($member1->getProfile('op_preset_sex'), 'MemberProfile', '->getProfile() returns an instance of MemberProfile if it is available');
$t->is($member1->getProfile('dummy'), null, '->getProfile() returns null if it is not available');
$t->isa_ok($member1->getProfile('op_preset_region', true, 1), 'MemberProfile', '->getProfile() returns an instance of MemberProfile to member 1');
$t->isa_ok($member1->getProfile('op_preset_region', true, 2), 'MemberProfile', '->getProfile() returns an instance of MemberProfile to member 2');
$t->is($member1->getProfile('op_preset_region', true, 3), null, '->getProfile() returns null to member 3');

//------------------------------------------------------------
$t->diag('Member::getConfig()');
$t->is($member1->getConfig('pc_address'), 'sns@example.com');
$t->is($member1->getConfig('dummy'), null);
$t->is($member1->getConfig('dummy', array()), array());
$t->is($member1->getConfig('dummy', 11111111111111111111), 11111111111111111111);

//------------------------------------------------------------
$t->diag('Member::setConfig()');
$t->is($member1->getConfig('foo'), null);
$member1->setConfig('foo', 'foo');
$t->is($member1->getConfig('foo'), 'foo');
$member1->setConfig('foo', 'bar');
$t->is($member1->getConfig('foo'), 'bar');
$member1->setConfig('foo', '1989-01-08', true);
$t->is($member1->getConfig('foo'), '1989-01-08');

//------------------------------------------------------------
$t->diag('Member::getFriends()');
$t->isa_ok($member1->getFriends(), 'Doctrine_Collection');
$t->isa_ok($member1->getFriends(10), 'Doctrine_Collection');
$t->isa_ok($member1->getFriends(null, true), 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('Member::countFriends()');
$t->todo();

//------------------------------------------------------------
$t->diag('Member::getNameAndCount()');
$t->todo('is($member1->getNameAndCount(), \'A (4)\')');
Doctrine::getTable('SnsConfig')->set('enable_friend_link', false);
$t->is($member1->getNameAndCount(), 'A');
Doctrine::getTable('SnsConfig')->set('enable_friend_link', true);

//------------------------------------------------------------
$t->diag('Member::getJoinCommunities()');
$result = $member1->getJoinCommunities();
$t->isa_ok($result, 'Doctrine_Collection');
$t->is($result->count(), 4);

//------------------------------------------------------------
$t->diag('Member::getFriendPreTo()');
$result = $member1->getFriendPreTo();
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('Member::countFriendPreTo()');
$t->is($member1->countFriendPreTo(), 1);

//------------------------------------------------------------
$t->diag('Member::getFriendPreFrom()');
$result = $member4->getFriendPreFrom();
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('Member::countFriendPreFrom()');
$t->is($member4->countFriendPreFrom(), 1);

//------------------------------------------------------------
$t->diag('Member::getImage()');
$t->isa_ok($member1->getImage(), 'MemberImage');

//------------------------------------------------------------
$t->diag('Member::getImageFileName()');
$t->isa_ok($member1->getImageFileName(), 'File');
$t->is((string)$member1->getImageFileName(), 'dummy_file3');
$t->is($member3->getImageFileName(), false);

//------------------------------------------------------------
$t->diag('Member::updateLastLoginTime()');
$t->ok(!$member1->getConfig('lastLogin'));
$member1->updateLastLoginTime();
$t->ok($member1->getConfig('lastLogin'));

//------------------------------------------------------------
$t->diag('Member::getLastLoginTime()');
$member1->setConfig('lastLogin', '2009-01-01 00:00:00', true);
$t->is($member1->getLastLoginTime(), 1230735600);

//------------------------------------------------------------
$t->diag('Member::isOnBlackList()');
$t->ok(!$member1->isOnBlackList());
$member1->setConfig('mobile_uid', 'TEST');
$t->ok($member1->isOnBlackList());
$member1->setConfig('mobile_uid', null);

//------------------------------------------------------------
$t->diag('Member::getInvitingMembers()');
$t->isa_ok($member4->getInvitingMembers(), 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('Member::getInviteMember()');
$t->isa_ok($member2->getInviteMember(), 'Member');

//------------------------------------------------------------
$t->diag('Member::getEmailAddress()');
$t->is($member1->getEmailAddress(), 'sns@example.com');
sfConfig::set('sf_app', 'mobile_frontend');
$t->is($member1->getEmailAddress(), 'sns@m.example.com');
$t->is($member1->getEmailAddress(true), 'sns@m.example.com');
$t->is($member2->getEmailAddress(), null);

//------------------------------------------------------------
$t->diag('Member::getEmailAddresses()');
$t->is($member1->getEmailAddresses(), array('sns@example.com', 'sns@m.example.com'));

//------------------------------------------------------------
$t->diag('Member::getMailAddressHash()');
$t->is($member1->getMailAddressHash(), 'ac049c237300');

//------------------------------------------------------------
$t->diag('Member::generateRoleId()');
$t->is($member1->generateRoleId($member1), 'self');
$t->is($member1->generateRoleId($member2), 'everyone');
$t->is($member1->generateRoleId($member5), 'blocked');

//------------------------------------------------------------
$t->diag('Member::delete()');
$t->todo();
