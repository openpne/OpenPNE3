<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

$communityMemberTable = Doctrine::getTable('CommunityMember');

//------------------------------------------------------------

$t->diag('CommunityMemberTable::retrieveByMemberIdAndCommunityId()');
$t->isa_ok($communityMemberTable->retrieveByMemberIdAndCommunityId(1, 1), 'CommunityMember',
  'retrieveByMemberIdAndCommunityId() returns a CommunityMember if member joins community');
$t->cmp_ok($communityMemberTable->retrieveByMemberIdAndCommunityId(1, 2), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member does not join community');
$t->cmp_ok($communityMemberTable->retrieveByMemberIdAndCommunityId(999, 1), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member does not exist');
$t->cmp_ok($communityMemberTable->retrieveByMemberIdAndCommunityId(1, 999), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if community does not exist');
$t->cmp_ok($communityMemberTable->retrieveByMemberIdAndCommunityId(999, 999), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member and community do not exist');

//------------------------------------------------------------

$t->diag('CommunityMemberTable::isMember()');
$t->cmp_ok($communityMemberTable->isMember(1, 1), '===', true,
  'isMember() returns true if member joins community');
$t->cmp_ok($communityMemberTable->isMember(1, 2), '===', false,
  'isMember() returns false if member does not join community');
$t->cmp_ok($communityMemberTable->isMember(999, 1), '===', false,
  'isMember() returns false if member does not exist');
$t->cmp_ok($communityMemberTable->isMember(1, 999), '===', false,
  'isMember() returns false if community does not exist');
$t->cmp_ok($communityMemberTable->isMember(999, 999), '===', false,
  'isMember() returns false if member and community do not exist');

//------------------------------------------------------------

$t->diag('CommunityMemberTable::isAdmin()');
$t->cmp_ok($communityMemberTable->isAdmin(1, 1), '===', true,
  'isAdmin() returns true if member joins community and position is admin');
$t->cmp_ok($communityMemberTable->isAdmin(2, 1), '===', false,
  'isAdmin() returns false if member joins community and position is not admin');
$t->cmp_ok($communityMemberTable->isAdmin(1, 2), '===', false,
  'isAdmin() returns false if member does not join community');
$t->cmp_ok($communityMemberTable->isAdmin(999, 1), '===', false,
  'isAdmin() returns false if member does not exist');
$t->cmp_ok($communityMemberTable->isAdmin(1, 999), '===', false,
  'isAdmin() returns false if community does not exist');
$t->cmp_ok($communityMemberTable->isAdmin(999, 999), '===', false,
  'isAdmin() returns false if member and community do not exist');

//------------------------------------------------------------

$t->diag('CommunityMemberTable::join()');

$t->cmp_ok($communityMemberTable->isMember(1, 2), '===', false, 'isMember() returns false');
$communityMemberTable->join(1, 2);
$t->cmp_ok($communityMemberTable->isMember(1, 2), '===', true, 'isMember() returns true');
$t->cmp_ok($communityMemberTable->isAdmin(1, 2), '===', false, 'isAdmin() returns false');

$message = 'join() throws exception if member already joins community';
$t->cmp_ok($communityMemberTable->isMember(1, 1), '===', true, 'isMember() returns true');
try {
  $communityMemberTable->join(1, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'join() throws exception if member does not exist';
try {
  $communityMemberTable->join(999, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'join() throws exception if community does not exist';
try {
  $communityMemberTable->join(1, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'join() throws exception if member and community do not exist';
try {
  $communityMemberTable->join(999, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

//------------------------------------------------------------

$t->diag('CommunityMemberTable::quit()');

$t->cmp_ok($communityMemberTable->isMember(2, 1), '===', true, 'isMember() returns true');
$communityMemberTable->quit(2, 1);
$t->cmp_ok($communityMemberTable->isMember(2, 1), '===', false, 'isMember() returns false');

$message = 'quit() throws exception if member is community admin';
$t->cmp_ok($communityMemberTable->isAdmin(1, 1), '===', true, 'isAdmin() returns true');
try {
  $communityMemberTable->quit(1, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if member does not join community';
try {
  $communityMemberTable->quit(3, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if member does not exist';
try {
  $communityMemberTable->quit(999, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if community does not exist';
try {
  $communityMemberTable->quit(1, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if member and community do not exist';
try {
  $communityMemberTable->quit(999, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}
