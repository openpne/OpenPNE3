<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

$member1 = Doctrine::getTable('Member')->find(1);
$member2 = Doctrine::getTable('Member')->find(2);
$member3 = Doctrine::getTable('Member')->find(3);
$member5 = Doctrine::getTable('Member')->find(5);
$anonymous = new opAnonymousMember();

$acl = opMemberAclBuilder::buildResource($member1, array($member1, $member2, $member3, $member5, $anonymous));

$t->ok($acl->isAllowed($member1, null, 'view'), 'The "self" role can view this member');
$t->ok($acl->isAllowed($member2, null, 'view'), 'The "friend" role can view this member');
$t->ok($acl->isAllowed($member3, null, 'view'), 'The "everyone" role can view this member');
$t->ok(!$acl->isAllowed($member5, null, 'view'), 'The "blocked" role can not view this member');
$t->ok(!$acl->isAllowed($anonymous, null, 'view'), 'The "anonymous" role can not view this member');

$t->ok($acl->isAllowed($member1->id, null, 'view'), 'The "self" role can view this member');
$t->ok($acl->isAllowed($member2->id, null, 'view'), 'The "friend" role can view this member');
$t->ok($acl->isAllowed($member3->id, null, 'view'), 'The "everyone" role can view this member');
$t->ok(!$acl->isAllowed($member5->id, null, 'view'), 'The "blocked" role can not view this member');
$t->ok(!$acl->isAllowed($anonymous->id, null, 'view'), 'The "anonymous" role can not view this member');
