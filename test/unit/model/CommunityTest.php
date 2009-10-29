<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(32, new lime_output_color());

//------------------------------------------------------------

$t->diag('CommunityTable::retrievesByMemberId()');
$communities = Doctrine::getTable('Community')->retrievesByMemberId(1);
$t->is(count($communities), 4, 'retrievesByMemberId returns 4 communities');

//------------------------------------------------------------

$t->diag('CommunityTable::getJoinCommunityListPager()');
$pager = Doctrine::getTable('Community')->getJoinCommunityListPager(1);
$t->isa_ok($pager, 'sfDoctrinePager', 'getJoinCommunityListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 4, 'getNbResults() returns 4');

$pager = Doctrine::getTable('Community')->getJoinCommunityListPager(999);
$t->isa_ok($pager, 'sfDoctrinePager', 'getJoinCommunityListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 0, 'getNbResults() returns 0');

//------------------------------------------------------------

$t->diag('CommunityTable::getCommunityMemberListPager()');
$pager = Doctrine::getTable('Community')->getCommunityMemberListPager(1);
$t->isa_ok($pager, 'sfDoctrinePager', 'getCommunityMemberListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 2, 'getNbResults() returns 2');

$pager = Doctrine::getTable('Community')->getCommunityMemberListPager(999);
$t->isa_ok($pager, 'sfDoctrinePager', 'getCommunityMemberListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 0, 'getNbResults() returns 0');

//------------------------------------------------------------

$t->diag('CommunityTable::getIdsByMemberId()');
$communityIds = Doctrine::getTable('Community')->getIdsByMemberId(1);
$t->is(count($communityIds), 4, 'getIdsByMemberId() returns 4 ids');
$t->is($communityIds, array(1, 3, 4, 5), 'getIdsByMemberId() returns array(1, 3, 4, 5)');

//------------------------------------------------------------

$t->diag('CommunityTable::getDefaultCommunities()');
$communities = Doctrine::getTable('Community')->getDefaultCommunities();
$t->is(count($communities), 2, 'getDefaultCommunities() returns 2 communities');

//------------------------------------------------------------

$t->diag('CommunityTable::getChangeAdminRequestCommunities()');
$communities = Doctrine::getTable('Community')->getChangeAdminRequestCommunities(2);
$t->is(count($communities), 1, 'getChangeAdminRequestCommunities() returns a community');

$communities = Doctrine::getTable('Community')->getChangeAdminRequestCommunities(1);
$t->cmp_ok($communities, '===' ,null, 'getChangeAdminRequestCommunities() returns null');

$communities = Doctrine::getTable('Community')->getChangeAdminRequestCommunities(999);
$t->cmp_ok($communities, '===' ,null, 'getChangeAdminRequestCommunities() returns null');

//------------------------------------------------------------
//------------------------------------------------------------

$community1 = Doctrine::getTable('Community')->find(1);
$community2 = Doctrine::getTable('Community')->find(2);
$community4 = Doctrine::getTable('Community')->find(4);

//------------------------------------------------------------

$t->diag('Community::getConfig()');
$t->is($community1->getConfig('description'), 'IDが1番のコミュニティ', 'getConfig(\'description\') returns right description');
$t->is($community1->getConfig('is_default'), true, 'getConfig(\'is_default\') returns true');

//------------------------------------------------------------
$t->diag('Community::getAdminMember()');
$t->is($community1->getAdminMember()->getId(), 1, 'getAdminMember() returns right admin member');
$t->is($community2->getAdminMember()->getId(), 2, 'getAdminMember() returns right admin member');

//------------------------------------------------------------

$t->diag('Community::isPrivilegeBelong()');
$t->is($community1->isPrivilegeBelong(1), true, 'isPrivilegeBelong() checks the member belonged');
$t->is($community2->isPrivilegeBelong(2), true, 'isPrivilegeBelong() checks the member belonged');
$t->is($community1->isPrivilegeBelong(3), false, 'isPrivilegeBelong() checks the member not belonged');
$t->is($community2->isPrivilegeBelong(1), false, 'isPrivilegeBelong() checks the member not belonged');

//------------------------------------------------------------
$t->diag('Community::isAdmin()');
$t->is($community1->isAdmin(1), true, 'isAdmin() returns true for admin');
$t->is($community1->isAdmin(2), false, 'isAdmin() returns false for not admin');

//------------------------------------------------------------
$t->diag('Community::countCommunityMembers()');
$t->is($community1->countCommunityMembers(), 2, 'countCommunityMembers() returns 2');

//------------------------------------------------------------

$t->diag('Community::getNameAndCount()');
$t->is($community1->getNameAndCount(), '最初のコミュニティ (2)', 'getNameAndCount() returns a string formated "%s (%d)"');
$t->is($community1->getNameAndCount('[%s] - %d'), '[最初のコミュニティ] - 2', 'getNameAndCount() returns a string formated "[%s] - %d"');

//------------------------------------------------------------

$t->diag('Community::getRegisterPoricy()');
$t->is($community1->getRegisterPoricy(), 'Everyone can join', 'getRegisterPoricy() returns "Everyone can join" for opened community');
$t->is($community2->getRegisterPoricy(), '%Community%\'s admin authorization needed', 'getRegisterPoricy() returns "Community\'s admin authorization needed" for closed community');

//------------------------------------------------------------

$t->diag('getChangeAdminRequestMember()');
$object = $community4->getChangeAdminRequestMember();
$t->ok(($object instanceof Member) && $object->getId() == 2, 'getChangeAdminRequestMember() returns an instance of Member 2');
$t->cmp_ok($community1->getChangeAdminRequestMember(), '===', null, 'getChangeAdminRequestMember() returns null');
