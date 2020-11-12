<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(39);

$community1 = Doctrine::getTable('Community')->findOneByName('CommunityA');
$community2 = Doctrine::getTable('Community')->findOneByName('CommunityB');
$community3 = Doctrine::getTable('Community')->findOneByName('CommunityC');
$community4 = Doctrine::getTable('Community')->findOneByName('CommunityD');
$community5 = Doctrine::getTable('Community')->findOneByName('CommunityE');

//------------------------------------------------------------
$t->diag('Community');
$t->diag('Community::getImageFileName()');
$t->is($community1->getImageFileName(), 'dummy_file');
$t->is($community2->getImageFileName(), '');

//------------------------------------------------------------
$t->diag('Community::getConfigs()');
$result = $community1->getConfigs();
$t->isa_ok($result, 'array');

//------------------------------------------------------------
$t->diag('Community::getConfig()');
$t->is($community1->getConfig('description'), 'IDが1番のコミュニティ', 'getConfig(\'description\') returns right description');
$t->is($community1->getConfig('is_default'), true, 'getConfig(\'is_default\') returns true');
$t->is($community1->getConfig('xxxxxxxxxx'), null, 'getConfig(\'xxxxxxxxxx\') returns null');


//------------------------------------------------------------
$t->diag('Community::setConfig()');
$community1->setConfig('foo', 'bar');
$t->is($community1->getConfig('foo'), 'bar', 'setConfig() set value of config');

//------------------------------------------------------------
$t->diag('Community::getMembers()');
$t->isa_ok($community1->getMembers(), 'Doctrine_Collection', 'getMembers() returns Doctrine_Collection object');
$t->isa_ok($community1->getMembers(1), 'Doctrine_Collection', 'getMembers(1) returns Doctrine_Collection object');
$t->isa_ok($community1->getMembers(1, true), 'Doctrine_Collection', 'getMembers(1, true) returns Doctrine_Collection object');

//------------------------------------------------------------
$t->diag('Community::getAdminMember()');
$t->is($community1->getAdminMember()->getId(), 1, 'getAdminMember() returns right admin member');
$t->is($community2->getAdminMember()->getId(), 2, 'getAdminMember() returns right admin member');

//------------------------------------------------------------
$t->diag('Community::getSubAdminMembers()');
$t->is($community1->getSubAdminMembers(), array(), 'getSubAdminMembers() returns empty array');
$result = $community5->getSubAdminMembers();
$t->isa_ok($result, 'Doctrine_Collection', 'getSubAdminMembers() returns Doctrine_Collection object');
$t->is($result->count(), 1, 'getSubAdminMembers() return Doctrine_Collection object that has 1 record');

//------------------------------------------------------------
$t->diag('Community::checkPrivilegeBelong()');
function checkPrivilegeBelong($object, $memberId)
{
  try
  {
    $object->checkPrivilegeBelong($memberId);
    return true;
  }
  catch (opPrivilegeException $e)
  {
    return false;
  }
}
$t->ok(checkPrivilegeBelong($community1, 1), 'checkPrivilegeBelong() is pass');
$t->ok(checkPrivilegeBelong($community2, 2), 'checkPrivilegeBelong() is pass');
$t->ok(!checkPrivilegeBelong($community1, 3), 'checkPrivilegeBelong() throw the opPrivilegeException');
$t->ok(!checkPrivilegeBelong($community2, 1), 'checkPrivilegeBelong() throw the opPrivilegeException');

//------------------------------------------------------------
$t->diag('Community::isPrivilegeBelong()');
$t->is($community1->isPrivilegeBelong(1), true, 'isPrivilegeBelong() checks the member belonged');
$t->is($community2->isPrivilegeBelong(2), true, 'isPrivilegeBelong() checks the member belonged');
$t->is($community1->isPrivilegeBelong(3), false, 'isPrivilegeBelong() checks the member not belonged');
$t->is($community2->isPrivilegeBelong(1), false, 'isPrivilegeBelong() checks the member not belonged');

//------------------------------------------------------------
$t->diag('Community::isAdmin()');
$t->is($community1->isAdmin(1), true, 'isAdmin() returns true for admin');
$t->is($community1->isAdmin(2), false, 'isAdmin() returns false for not admin');

//------------------------------------------------------------
$t->diag('Community::countCommunityMembers()');
$t->todo('countCommunityMembers() returns 2');

//------------------------------------------------------------
$t->diag('Community::getNameAndCount()');
$t->todo('getNameAndCount() returns a string formated "%s (%d)"');
$t->todo('getNameAndCount() returns a string formated "[%s] - %d"');

//------------------------------------------------------------
$t->diag('Community::getRegisterPolicy()');
$t->is($community1->getRegisterPolicy(), 'Everyone can join', 'getRegisterPolicy() returns "Everyone can join" for opened community');
$t->is($community2->getRegisterPolicy(), '%Community%\'s admin authorization needed', 'getRegisterPolicy() returns "Community\'s admin authorization needed" for closed community');

//------------------------------------------------------------
$t->diag('Community::getChangeAdminRequestMember()');
$object = $community4->getChangeAdminRequestMember();
$t->ok(($object instanceof Member) && $object->getId() == 2, 'getChangeAdminRequestMember() returns an instance of Member 2');
$t->cmp_ok($community1->getChangeAdminRequestMember(), '===', null, 'getChangeAdminRequestMember() returns null');

//------------------------------------------------------------
$t->diag('Community::generateRoleId()');
$t->is($community1->generateRoleId(Doctrine::getTable('Member')->find(1)), 'admin', 'generateRoleId() returns "admin"');
$t->is($community1->generateRoleId(Doctrine::getTable('Member')->find(2)), 'member', 'generateRoleId() returns "member"');
$t->is($community1->generateRoleId(Doctrine::getTable('Member')->find(3)), 'everyone', 'generateRoleId() returns "everyone"');
$t->is($community5->generateRoleId(Doctrine::getTable('Member')->find(2)), 'sub_admin', 'generateRoleId() returns "sub_admin"');

//------------------------------------------------------------
$t->diag('Community: Cascading Delete');
$conn->beginTransaction();

$fileId = $community3->file_id;
$community3->delete($conn);

$t->ok(!Doctrine_Core::getTable('Community')->find($community3->id), 'community is deleted.');
$t->ok(!Doctrine_Core::getTable('File')->find($fileId), 'file is deleted.');
$t->ok(!Doctrine_Core::getTable('FileBin')->find($fileId), 'file_bin is deleted.');

$conn->rollback();
