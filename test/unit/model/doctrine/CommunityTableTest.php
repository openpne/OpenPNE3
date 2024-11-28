<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
$user = sfContext::getInstance()->getUser();
$user->setAuthenticated(true);
$user->setMemberId(1);

$t = new lime_test(51, new lime_output_color());

$table = Doctrine::getTable('Community');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$member5 = Doctrine::getTable('Member')->findOneByName('E');
$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');
$community4 = Doctrine::getTable('Community')->findOneByName('CommunityD');
$community5 = Doctrine::getTable('Community')->findOneByName('CommunityE');

//------------------------------------------------------------
$t->diag('CommunityTable');
$t->diag('CommunityTable::retrievesByMemberId()');
$communities = $table->retrievesByMemberId(1);
$t->is(count($communities), 4, 'retrievesByMemberId() returns 4 communities');
$communities = $table->retrievesByMemberId(1, 1);
$t->is(count($communities), 1, 'retrievesByMemberId() returns 1 communities');
$communities = $table->retrievesByMemberId(1, 1, true);
$t->is(count($communities), 1, 'retrievesByMemberId() returns 1 communities');
$t->ok(!$table->retrievesByMemberId(999), 'retrievesByMemberId() return null');

//------------------------------------------------------------
$t->diag('CommunityTable::getJoinCommunityListPager()');
$pager = $table->getJoinCommunityListPager(1);
$t->isa_ok($pager, 'sfDoctrinePager', 'getJoinCommunityListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 4, 'getNbResults() returns 4');

$pager = $table->getJoinCommunityListPager(999);
$t->isa_ok($pager, 'sfDoctrinePager', 'getJoinCommunityListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 0, 'getNbResults() returns 0');

//------------------------------------------------------------
$t->diag('CommunityTable::getCommunityMemberListPager()');
$pager = $table->getCommunityMemberListPager(1);
$t->todo('getCommunityMemberListPager() returns a sfDoctrinePager');
$t->todo('getNbResults() returns 2');

$pager = $table->getCommunityMemberListPager(999);
$t->todo('getCommunityMemberListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 0, 'getNbResults() returns 0');

//------------------------------------------------------------
$t->diag('CommunityTable::getIdsByMemberId()');
$communityIds = $table->getIdsByMemberId(1);
$t->is(count($communityIds), 4, 'getIdsByMemberId() returns 4 ids');
$t->is($communityIds, array(1, 3, 4, 5), 'getIdsByMemberId() returns array(1, 3, 4, 5)');

//------------------------------------------------------------
$t->diag('CommunityTable::getDefaultCommunities()');
$communities = $table->getDefaultCommunities();
$t->todo('getDefaultCommunities() returns 2 communities');

//------------------------------------------------------------
$t->diag('CommunityTable::getPositionRequestCommunities()');
$communities = $table->getPositionRequestCommunities();
$t->cmp_ok($communities, '===' ,null, 'getPositionRequestCommunities() returns empty collection');

$communities = $table->getPositionRequestCommunities('admin', 2);
$t->is(count($communities), 1, 'getPositionRequestCommunities() returns a community');

$communities = $table->getPositionRequestCommunities('admin', 1);
$t->cmp_ok($communities, '===' ,null, 'getPositionRequestCommunities() returns null');

$communities = $table->getPositionRequestCommunities('admin', 999);
$t->cmp_ok($communities, '===' ,null, 'getPositionRequestCommunities() returns null');

//------------------------------------------------------------
$t->diag('CommunityTable::countPositionRequestCommunities()');
$t->is($table->countPositionRequestCommunities(), 0, 'countChangeAdminRequestCommunities() returns 0');
$t->is($table->countPositionRequestCommunities('admin', 1), 0, 'countChangeAdminRequestCommunities() returns 0');
$t->is($table->countPositionRequestCommunities('admin', 2), 1, 'countChangeAdminRequestCommunities() returns 1');
$t->is($table->countPositionRequestCommunities('admin', 999), 0, 'countChangeAdminRequestCommunities() returns 0');

//------------------------------------------------------------
$t->diag('ACL Test');
$t->ok($community1->isAllowed($member1, 'view'));
$t->ok($community1->isAllowed($member2, 'view'));
$t->ok($community1->isAllowed($member1, 'edit'));
$t->ok(!$community1->isAllowed($member2, 'edit'));

//------------------------------------------------------------
$t->diag('CommunityTable::adminConfirmList()');
$event = new sfEvent('subject', 'name', array('member' => $member1));
$t->ok(!CommunityTable::adminConfirmList($event));

$event = new sfEvent('subject', 'name', array('member' => $member2));
$t->ok(CommunityTable::adminConfirmList($event));
$t->is(count($event->getReturnValue()), 1);

//------------------------------------------------------------
$t->diag('CommunityTable::subAdminConfirmList()');
$event = new sfEvent('subject', 'name', array('member' => $member1));
$t->ok(!CommunityTable::subAdminConfirmList($event));

$event = new sfEvent('subject', 'name', array('member' => $member3));
$t->ok(CommunityTable::subAdminConfirmList($event));
$t->is(count($event->getReturnValue()), 1);

//------------------------------------------------------------
$t->diag('CommunityTable::processAdminConfirm()');
$event = new sfEvent('subject', 'name', array('member' => $member2, 'id' => $community4->getId(), 'is_accepted' => true));

$t->ok($community4->isAdmin($member1->getId()));
$t->ok(!$community4->isAdmin($member2->getId()));
$t->ok(CommunityTable::processAdminConfirm($event));
$t->ok(!$community4->isAdmin($member1->getId()));
$t->ok($community4->isAdmin($member2->getId()));

$cm1 = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member1->getId(), $community4->getId());
$cm1->addPosition('admin');

$cm2 = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member2->getId(), $community4->getId());
$cm2->addPosition('admin_confirm');
$cm2->removePosition('admin');

$event = new sfEvent('subject', 'name', array('member' => $member2, 'id' => $community4->getId(), 'is_accepted' => false));
$t->ok($community4->isAdmin($member1->getId()));
$t->ok(!$community4->isAdmin($member2->getId()));
$t->ok(CommunityTable::processAdminConfirm($event));
$t->ok($community4->isAdmin($member1->getId()));
$t->ok(!$community4->isAdmin($member2->getId()));

$event = new sfEvent('subject', 'name', array('member' => $member2, 'id' => 999, 'is_accepted' => false));
$t->ok(!CommunityTable::processAdminConfirm($event));

//------------------------------------------------------------
$t->diag('CommunityTable::processSubAdminConfirm()');
$event = new sfEvent('subject', 'name', array('member' => $member3, 'id' => $community5->getId(), 'is_accepted' => true));

$t->ok(!Doctrine::getTable('CommunityMember')->isSubAdmin($member3->id, $community5->id));
$t->ok(CommunityTable::processSubAdminConfirm($event));
$t->ok(Doctrine::getTable('CommunityMember')->isSubAdmin($member3->id, $community5->id));

$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member3->id, $community5->id);
$cm->removeAllPosition();
$cm->addPosition('sub_admin_confirm');

$event = new sfEvent('subject', 'name', array('member' => $member3, 'id' => $community5->getId(), 'is_accepted' => false));
$t->ok(!Doctrine::getTable('CommunityMember')->isSubAdmin($member3->id, $community5->id));
$t->ok(CommunityTable::processSubAdminConfirm($event));
$t->ok(!Doctrine::getTable('CommunityMember')->isSubAdmin($member3->id, $community5->id));

$event = new sfEvent('subject', 'name', array('member' => $member3, 'id' => 999, 'is_accepted' => false));
$t->ok(!CommunityTable::processSubAdminConfirm($event));
