<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(null, new lime_output_color());

$table = Doctrine::getTable('CommunityMember');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');

//------------------------------------------------------------
$t->diag('CommunityMember');
$t->diag('CommunityMember::retrieveByMemberIdAndCommunityId()');
$t->isa_ok($table->retrieveByMemberIdAndCommunityId(1, 1), 'CommunityMember',
  'retrieveByMemberIdAndCommunityId() returns a CommunityMember if member joins community');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(1, 2), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member does not join community');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(999, 1), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member does not exist');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(1, 999), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if community does not exist');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(999, 999), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member and community do not exist');

//------------------------------------------------------------
$t->diag('CommunityMember::isMember()');
$t->cmp_ok($table->isMember(1, 1), '===', true,
  'isMember() returns true if member joins community');
$t->cmp_ok($table->isMember(1, 2), '===', false,
  'isMember() returns false if member does not join community');
$t->cmp_ok($table->isMember(999, 1), '===', false,
  'isMember() returns false if member does not exist');
$t->cmp_ok($table->isMember(1, 999), '===', false,
  'isMember() returns false if community does not exist');
$t->cmp_ok($table->isMember(999, 999), '===', false,
  'isMember() returns false if member and community do not exist');

//------------------------------------------------------------
$t->diag('CommunityMember::isAdmin()');
$t->cmp_ok($table->isAdmin(1, 1), '===', true,
  'isAdmin() returns true if member joins community and position is admin');
$t->cmp_ok($table->isAdmin(2, 1), '===', false,
  'isAdmin() returns false if member joins community and position is not admin');
$t->cmp_ok($table->isAdmin(1, 2), '===', false,
  'isAdmin() returns false if member does not join community');
$t->cmp_ok($table->isAdmin(999, 1), '===', false,
  'isAdmin() returns false if member does not exist');
$t->cmp_ok($table->isAdmin(1, 999), '===', false,
  'isAdmin() returns false if community does not exist');
$t->cmp_ok($table->isAdmin(999, 999), '===', false,
  'isAdmin() returns false if member and community do not exist');

//------------------------------------------------------------
$t->diag('CommunityMember::join()');

$t->cmp_ok($table->isMember(1, 2), '===', false, 'isMember() returns false');
$table->join(1, 2);
$t->cmp_ok($table->isMember(1, 2), '===', true, 'isMember() returns true');
$t->cmp_ok($table->isAdmin(1, 2), '===', false, 'isAdmin() returns false');

$message = 'join() throws exception if member already joins community';
$t->cmp_ok($table->isMember(1, 1), '===', true, 'isMember() returns true');
try {
  $table->join(1, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'join() throws exception if member does not exist';
try {
  $table->join(999, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'join() throws exception if community does not exist';
try {
  $table->join(1, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'join() throws exception if member and community do not exist';
try {
  $table->join(999, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

//------------------------------------------------------------
$t->diag('CommunityMember::quit()');

$t->cmp_ok($table->isMember(2, 1), '===', true, 'isMember() returns true');
$table->quit(2, 1);
$t->cmp_ok($table->isMember(2, 1), '===', false, 'isMember() returns false');

$message = 'quit() throws exception if member is community admin';
$t->cmp_ok($table->isAdmin(1, 1), '===', true, 'isAdmin() returns true');
try {
  $table->quit(1, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if member does not join community';
try {
  $table->quit(3, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if member does not exist';
try {
  $table->quit(999, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if community does not exist';
try {
  $table->quit(1, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

$message = 'quit() throws exception if member and community do not exist';
try {
  $table->quit(999, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->pass($message);
}

//------------------------------------------------------------
$t->diag('CommunityMember::getCommunityMembersPre()');
$result = $table->getCommunityMembersPre(1);
$t->isa_ok($result, 'Doctrine_Collection', 'getCommunityMembersPre() returns Doctrine_Collection object');

$result = $table->getCommunityMembersPre(5);
$t->is($result, array(), 'getCommunityMembersPre() returns empty array');

//------------------------------------------------------------
$t->diag('CommunityMember::countCommunityMembersPre()');
$t->is($table->countCommunityMembersPre(1), 1, 'countCommunityMembersPre() returns 1');
$t->is($table->countCommunityMembersPre(5), 0, 'countCommunityMembersPre() returns 0');

//------------------------------------------------------------
$t->diag('CommunityMember::getCommunityMembers()');
$result = $table->getCommunityMembers(1);
$t->isa_ok($result, 'Doctrine_Collection', 'getCommunityMembers() returns Doctrine_Collection object');

//------------------------------------------------------------
$t->diag('CommunityMember::requestChangeAdmin()');
$object = $table->retrieveByMemberIdAndCommunityId(2, 3);
$t->cmp_ok($object->getPosition(), '===', '', 'The second_member position is "" in the community_3');
$table->requestChangeAdmin(2, 3, 1);
$object = $table->retrieveByMemberIdAndCommunityId(2, 3);
$t->cmp_ok($object->getPosition(), '===', 'admin_confirm', 'The second_member position is "admin_confirm" in the community_3');

$message = "requestChangeAdmin() throws exception if fromMember is not community's admin";
try {
  $table->requestChangeAdmin(3, 5, 2);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "Requester isn't community's admin.", $message);
}

$message = "requestChangeAdmin() throws exception if member is already position of something";
try {
  $table->requestChangeAdmin(2, 4, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "This member is already position of something.", $message);
}

$message = "requestChangeAdmin() throws exception if the community is invalid";
try {
  $table->requestChangeAdmin(2, 999, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "Requester isn't community's admin.", $message);
}

$message = "requestChangeAdmin() throws exception if the member is invalid"; 
try {
  $table->requestChangeAdmin(999, 1, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "Invalid community member.", $message);
}

//------------------------------------------------------------
$t->diag('CommunityMember::changeAdmin()');
$t->cmp_ok($table->isAdmin(2, 4), '===', false, 'isAdmin() returns false');
$t->cmp_ok($table->isAdmin(1, 4), '===', true, 'isAdmin() returns true');
$table->changeAdmin(2, 4);
$t->cmp_ok($table->isAdmin(2, 4), '===', true, 'isAdmin() returns true');
$t->cmp_ok($table->isAdmin(1, 4), '===', false, 'isAdmin() returns false');


$message = "changeAdmin() throws exception if the member position isn't \"admin_confirm\""; 
try {
  $table->changeAdmin(2, 5);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', 'This member position isn\'t "admin_confirm".', $message);
}

$message = "changeAdmin() throws exception if the member is invalid"; 
try {
  $table->changeAdmin(999, 5);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', 'Invalid community member.', $message);
}

$message = "changeAdmin() throws exception if the community is invalid"; 
try {
  $table->changeAdmin(2, 999);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', 'Invalid community member.', $message);
}

//------------------------------------------------------------
$t->diag('ACL Test');
$cm = $table->retrieveByMemberIdAndCommunityId($member1->id, $community1->id);
$t->ok($cm->isAllowed($member1, 'view'));
$t->ok(!$cm->isAllowed($member2, 'view'));
$t->ok(!$cm->isAllowed($member3, 'view'));
$t->ok($cm->isAllowed($member1, 'edit'));
$t->ok(!$cm->isAllowed($member2, 'edit'));
$t->ok(!$cm->isAllowed($member3, 'edit'));

//------------------------------------------------------------
$t->diag('CommunityMemberTable::joinConfirmList()');
$event = new sfEvent('subject', 'name', array('member' => $member1));
$t->ok(CommunityMemberTable::joinConfirmList($event));
$t->is(count($event->getReturnValue()), 1);

//------------------------------------------------------------
$t->diag('CommunityMemberTable::processJoinConfirm()');
$cm = $table->retrieveByMemberIdAndCommunityId(4, 5);
$t->is($cm->getPosition(), 'pre');
$event = new sfEvent('subject', 'name', array('id' => $cm->id, 'is_accepted' => true));
$t->ok(CommunityMemberTable::processJoinConfirm($event));
$t->is($cm->getPosition(), '');
$cm->setPosition('pre');
$cm->save();
$event = new sfEvent('subject', 'name', array('id' => $cm->id, 'is_accepted' => false));
$t->is($cm->getPosition(), 'pre');
$t->ok(CommunityMemberTable::processJoinConfirm($event));
$t->is($cm->getPosition(), 'pre');
