<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('CommunityPeer::getJoinCommunityListPager()');
$pager = CommunityPeer::getJoinCommunityListPager(1);
$t->isa_ok($pager, 'sfPropelPager', 'getJoinCommunityListPager() returns a sfPropelPager');
$t->is($pager->getNbResults(), 1, 'getNbResults() returns 1');

$pager = CommunityPeer::getJoinCommunityListPager(999);
$t->isa_ok($pager, 'sfPropelPager', 'getJoinCommunityListPager() returns a sfPropelPager');
$t->is($pager->getNbResults(), 0, 'getNbResults() returns 0');

//------------------------------------------------------------

$t->diag('CommunityPeer::getCommunityMemberListPager()');
$pager = CommunityPeer::getCommunityMemberListPager(1);
$t->isa_ok($pager, 'sfPropelPager', 'getCommunityMemberListPager() returns a sfPropelPager');
$t->is($pager->getNbResults(), 2, 'getNbResults() returns 2');

$pager = CommunityPeer::getCommunityMemberListPager(999);
$t->isa_ok($pager, 'sfPropelPager', 'getCommunityMemberListPager() returns a sfPropelPager');
$t->is($pager->getNbResults(), 0, 'getNbResults() returns 0');
