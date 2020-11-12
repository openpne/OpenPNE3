<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(9, new lime_output_color());

$communityCategory1 = Doctrine::getTable('CommunityCategory')->findOneByName('CategoryA');

//------------------------------------------------------------
$t->diag('CommunityCategory');
$t->diag('CommunityCategory::__toString()');
$t->is((string)$communityCategory1, 'CategoryA');

//------------------------------------------------------------
$t->diag('CommunityCategory::save()');
$newCommunityCategory1 = new CommunityCategory();
$newCommunityCategory1->getName('newParentCategory');
$newCommunityCategory1->save();
$t->is($newCommunityCategory1->getLevel(), 0);

$newCommunityCategory2 = new CommunityCategory();
$newCommunityCategory2->setTreeKey($newCommunityCategory1->getId());
$newCommunityCategory2->getName('newCategory');
$newCommunityCategory2->save();
$t->is($newCommunityCategory2->getLevel(), 1);

//------------------------------------------------------------
$t->diag('CommunityCategory::getForm()');
$t->isa_ok($communityCategory1->getForm(), 'CommunityCategoryForm');

//------------------------------------------------------------
$t->diag('CommunityCategory::getChildren()');
$t->isa_ok($communityCategory1->getChildren(), 'Doctrine_Collection');
$t->is(count($communityCategory1->getChildren()), 0);

$communityCategory = Doctrine::getTable('CommunityCategory')->find(1);
$category = $communityCategory->getChildren();
$t->todo('is(count($category), 2)');
$t->is($category[0]->getName(), 'CategoryB');
$t->is($category[1]->getName(), 'CategoryA');
