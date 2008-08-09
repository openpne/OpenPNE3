<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('CommunityPeer::getJoinCommunityListPager()');
$pager = CommunityPeer::getJoinCommunityListPager(1);
$t->isa_ok($pager, 'sfPropelPager', 'getJoinCommunityListPager() returns a sfPropelPager');

//------------------------------------------------------------

$t->diag('CommunityPeer::getCommunityMemberListPager()');
$pager = CommunityPeer::getCommunityMemberListPager(1);
$t->isa_ok($pager, 'sfPropelPager', 'getCommunityMemberListPager() returns a sfPropelPager');
