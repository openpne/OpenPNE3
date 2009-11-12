<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(3, new lime_output_color());

$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');

//------------------------------------------------------------
$t->diag('CommunityMember::generateRoleId()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member1->id, $community1->id);
$t->is($cm->generateRoleId($member1), 'admin');
$t->is($cm->generateRoleId($member2), 'member');
$t->is($cm->generateRoleId($member3), 'everyone');
