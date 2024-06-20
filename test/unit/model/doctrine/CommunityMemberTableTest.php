<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

opMailSend::initialize();
Zend_Mail::setDefaultTransport(new opZendMailTransportMock());

$t = new lime_test(82, new lime_output_color());
$table = Doctrine::getTable('CommunityMember');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');
$community5 = Doctrine::getTable('Community')->findOneByName('CommunityE');

//------------------------------------------------------------
$t->diag('CommunityMemberTable');
$t->diag('CommunityMemberTable::retrieveByMemberIdAndCommunityId()');
$t->isa_ok($table->retrieveByMemberIdAndCommunityId(1, 1), 'CommunityMember',
  'retrieveByMemberIdAndCommunityId() returns a CommunityMember if member joins community');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(1, 2), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member does not join community');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(1000, 1), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member does not exist');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(1, 999), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if community does not exist');
$t->cmp_ok($table->retrieveByMemberIdAndCommunityId(999, 999), '===', false,
  'retrieveByMemberIdAndCommunityId() returns NULL if member and community do not exist');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::isMember()');
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
$t->diag('CommunityMemberTable::isAdmin()');
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
$t->diag('CommunityMemberTable::isSubAdmin()');
$t->cmp_ok($table->isSubAdmin(2, 5), '===', true,
  'isSubAdmin() returns true if member joins community and position is sub_admin');
$t->cmp_ok($table->isSubAdmin(1, 5), '===', false,
  'isSubAdmin() returns false if member joins community and position is not sub_admin');
$t->cmp_ok($table->isSubAdmin(5, 5), '===', false,
  'isSubAdmin() returns false if member does not join community');
$t->cmp_ok($table->isSubAdmin(999, 5), '===', false,
  'isSubAdmin() returns false if member does not exist');
$t->cmp_ok($table->isSubAdmin(1, 999), '===', false,
  'isSubAdmin() returns false if community does not exist');
$t->cmp_ok($table->isSubAdmin(999, 999), '===', false,
  'isSubAdmin() returns false if member and community do not exist');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::join()');

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
$t->todo($message);

$message = 'join() throws exception if community does not exist';
$t->todo($message);

$message = 'join() throws exception if member and community do not exist';
$t->todo($message);

//------------------------------------------------------------
$t->diag('CommunityMemberTable::quit()');

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
$t->todo($message);

$message = 'quit() throws exception if community does not exist';
$t->todo($message);

$message = 'quit() throws exception if member and community do not exist';
$t->todo($message);

//------------------------------------------------------------
$t->diag('CommunityMemberTable::getCommunitySubAdmin()');
$result = $table->getCommunitySubAdmin(1);
$t->is(count($result), 0, 'getCommunitySubAdmin() returns empty');
$result = $table->getCommunitySubAdmin(5);
$t->isa_ok($result, 'Doctrine_Collection', 'getCommunitySubAdmin() returns Doctrine_Collection object');
$t->is($result->count(), 1, 'getCommunitySubAdmin() return Doctrine_Collection that has 1 record');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::getCommunityMembersPre()');
$result = $table->getCommunityMembersPre(1);
$t->isa_ok($result, 'Doctrine_Collection', 'getCommunityMembersPre() returns Doctrine_Collection object');

$result = $table->getCommunityMembersPre(5);
$t->is($result, array(), 'getCommunityMembersPre() returns empty array');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::countCommunityMembersPre()');
$t->is($table->countCommunityMembersPre(1), 1, 'countCommunityMembersPre() returns 1');
$t->is($table->countCommunityMembersPre(5), 0, 'countCommunityMembersPre() returns 0');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::getCommunityMembers()');
$result = $table->getCommunityMembers(1);
$t->isa_ok($result, 'Doctrine_Collection', 'getCommunityMembers() returns Doctrine_Collection object');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::requestChangeAdmin()');
$object = $table->retrieveByMemberIdAndCommunityId(2, 3);
$t->ok(!$object->hasPosition('admin_confirm'), 'The second_member has not any position in the community_3');
$table->requestChangeAdmin(2, 3, 1);
$object = $table->retrieveByMemberIdAndCommunityId(2, 3);
$t->ok($object->hasPosition('admin_confirm'), 'The second_member has "admin_confirm" position in the community_3');

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
$t->todo($message);

//------------------------------------------------------------
$t->diag('CommunityMemberTable::requestSubAdmin()');
$object = $table->retrieveByMemberIdAndCommunityId(5, 5);
$t->ok(!$object->hasPosition('sub_admin_confirm'), 'The third_member has not any position in the community_5');
$table->requestSubAdmin(5, 5, 1);
$object = $table->retrieveByMemberIdAndCommunityId(5, 5);
$t->ok($object->hasPosition('sub_admin_confirm'), 'The third_member has "admin_confirm" position in the community_5');

$message = "requestSubAdmin() throws exception if fromMember is not community's admin";
try {
  $table->requestSubAdmin(5, 5, 2);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "Requester isn't community's admin.", $message);
}

$message = "requestSubAdmin() throws exception if member is already position of something";
try {
  $table->requestSubAdmin(2, 4, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "This member is already position of something.", $message);
}

$message = "requestSubAdmin() throws exception if the community is invalid";
try {
  $table->requestSubAdmin(2, 999, 1);
  $t->fail($message);
} catch (Exception $e) {
  $t->cmp_ok($e->getMessage(), '===', "Requester isn't community's admin.", $message);
}

$message = "requestSubAdmin() throws exception if the member is invalid"; 
$t->todo($message);

//------------------------------------------------------------
$t->diag('CommunityMemberTable::changeAdmin()');
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
$t->diag('CommunityMemberTable::addSubAdmin()');
$t->cmp_ok($table->isSubAdmin(3, 5), '===', false, 'isSubAdmin() returns false');
$table->addSubAdmin(3, 5);
$t->cmp_ok($table->isSubAdmin(3, 5), '===', true, 'isSubAdmin() returns true');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::getMemberIdsByCommunityId()');
$t->todo();

//------------------------------------------------------------
$t->diag('ACL Test');
$cm = $table->retrieveByMemberIdAndCommunityId($member1->id, $community1->id);
$t->ok($cm->isAllowed($member1, 'view'), 'isAllowed() returns true');
$t->ok(!$cm->isAllowed($member2, 'view'), 'isAllowed() returns false');
$t->ok(!$cm->isAllowed($member3, 'view'), 'isAllowed() returns false');
$t->ok($cm->isAllowed($member1, 'edit'), 'isAllowed() return true');
$t->ok(!$cm->isAllowed($member2, 'edit'), 'isAllowed() return false');
$t->ok(!$cm->isAllowed($member3, 'edit'), 'isAllowed() returns false');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::joinConfirmList()');
$event = new sfEvent('subject', 'name', array('member' => $member1));
$t->ok(CommunityMemberTable::joinConfirmList($event), 'joinConfirmList() returns true');
$t->is(count($event->getReturnValue()), 1, 'return value of event is 1');

//------------------------------------------------------------
$t->diag('CommunityMemberTable::processJoinConfirm()');
$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId(4, 5);
$t->ok($cm->getIsPre(), 'the CommunityMember is pre');

$event = new sfEvent('subject', 'name', array('id' => $cm->id, 'is_accepted' => true));
$t->ok(CommunityMemberTable::processJoinConfirm($event), 'processJoinConfirm() returns true');

$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId(4, 5);
$t->ok(!$cm->getIsPre(), 'the CommunityMember is not pre');

$cm->setIsPre(true);
$cm->save();

$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId(4, 5);
$t->ok($cm->getIsPre(), 'the CommunityMember is pre');

$event = new sfEvent('subject', 'name', array('id' => $cm->id, 'is_accepted' => false));
$t->ok(CommunityMemberTable::processJoinConfirm($event), 'processJoinConfirm() returns true');

$cm = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId(4, 5);
$t->ok(!$cm, 'the CommunityMember is deleted');
