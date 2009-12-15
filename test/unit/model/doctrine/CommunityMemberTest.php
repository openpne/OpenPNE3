<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(13, new lime_output_color());

$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');
$community5 = Doctrine::getTable('Community')->findOneByName('CommunityE');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$member4 = Doctrine::getTable('Member')->findOneByName('D');
$member5 = Doctrine::getTable('Member')->findOneByName('E');

//------------------------------------------------------------
$t->diag('CommunityMember::generateRoleId()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member1->id, $community1->id);
$t->is($cm->generateRoleId($member1), 'admin');
$t->is($cm->generateRoleId($member2), 'member');
$t->is($cm->generateRoleId($member3), 'everyone');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member2->id, $community5->id);
$t->is($cm->generateRoleId($member2), 'sub_admin');

//------------------------------------------------------------
$t->diag('CommunityMember::getPositions()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member1->id, $community1->id);
$t->is($cm->getPositions(), array('admin'));

//------------------------------------------------------------
$t->diag('CommunityMember::hasPosition()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member1->id, $community1->id);
$t->ok($cm->hasPosition('admin'));
$t->ok(!$cm->hasPosition('sub_admin'));

//------------------------------------------------------------
$t->diag('CommunityMember::addPosition()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member5->id, $community5->id);
$t->is($cm->getPositions(), array());
$cm->addPosition('sub_admin');
$t->is($cm->getPositions(), array('sub_admin'));

//------------------------------------------------------------
$t->diag('CommunityMember::removePosition()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member2->id, $community5->id);
$t->is($cm->getPositions(), array('sub_admin'));
$cm->removePosition('sub_admin');
$t->is($cm->getPositions(), array());

//------------------------------------------------------------
$t->diag('CommunityMember::removeAllPosition()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member3->id, $community5->id);
$t->is($cm->getPositions(), array('sub_admin_confirm'));
$cm->removeAllPosition();
$t->is($cm->getPositions(), array());
