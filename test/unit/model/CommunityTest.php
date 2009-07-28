<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(12, new lime_output_color());

//------------------------------------------------------------

$t->diag('CommunityTable::retrievesByMemberId()');
$communities = Doctrine::getTable('Community')->retrievesByMemberId(1);
$t->is(count($communities), 3, 'retrievesByMemberId returns 3 communities');

//------------------------------------------------------------

$t->diag('CommunityTable::getJoinCommunityListPager()');
$pager = Doctrine::getTable('Community')->getJoinCommunityListPager(1);
$t->isa_ok($pager, 'sfDoctrinePager', 'getJoinCommunityListPager() returns a sfDoctrinePager');
$t->is($pager->getNbResults(), 3, 'getNbResults() returns 3');

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
$t->is(count($communityIds), 3, 'getIdsByMemberId() returns 3 ids');
$t->is($communityIds, array(1, 3, 4), 'getIdsByMemberId() returns array(1, 3, 4)');

//------------------------------------------------------------

$t->diag('CommunityTable::getDefaultCommunities()');
$communities = Doctrine::getTable('Community')->getDefaultCommunities();
$t->is(count($communities), 2, 'getDefaultCommunities() returns 2 communities');
