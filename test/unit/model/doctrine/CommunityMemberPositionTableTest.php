<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());
$table = Doctrine::getTable('CommunityMemberPosition');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');

//------------------------------------------------------------
$t->diag('CommunityMemberPositionTable');
$t->diag('CommunityMemberPositionTable::getPositionsByMemberIdAndCommunityId()');
$t->is($table->getPositionsByMemberIdAndCommunityId($member1->id, $community1->id), array('admin'));
$t->is($table->getPositionsByMemberIdAndCommunityId($member2->id, $community1->id), array());
$t->is($table->getPositionsByMemberIdAndCommunityId(999, $community1->id), array());
$t->is($table->getPositionsByMemberIdAndCommunityId($member1->id, 999), array());
