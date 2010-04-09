<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(12, new lime_output_color());

$gadget1 = Doctrine::getTable('Gadget')->findOneByName('languageSelecterBox');
$gadget3 = Doctrine::getTable('Gadget')->find(3);
$newGadget1 = new Gadget();
$newGadget2 = new Gadget();
$newGadget2->setType('top');

//------------------------------------------------------------
$t->diag('Gadget');
$t->diag('Gadet::preSave()');
$newGadget2->save();
$t->is($newGadget2->getSortOrder(), 20);

//------------------------------------------------------------
$t->diag('Gadet::getComponentModule()');
$t->is($gadget1->getComponentModule(), 'default');
$t->ok(!$newGadget1->getComponentModule());

//------------------------------------------------------------
$t->diag('Gadet::getComponentAction()');
$t->is($gadget1->getComponentAction(), 'languageSelecterBox');
$t->ok(!$newGadget1->getComponentAction());

//------------------------------------------------------------
$t->diag('Gadet::isEnabled()');
$t->ok($gadget1->isEnabled());
$t->ok(!$newGadget1->isEnabled());

//------------------------------------------------------------
$t->diag('Gadet::getConfig()');
$t->is($gadget3->getConfig('row'), 1);
$t->is($gadget3->getConfig('col'), 3);
$t->ok(!$gadget3->getConfig('xxxxxxxxxx'));

//------------------------------------------------------------
$t->diag('Gadet::generateRoleId()');
$member1 = Doctrine::getTable('Member')->find(1);
$anonymousMember = new opAnonymousMember();
$t->is($gadget1->generateRoleId($member1), 'everyone');
$t->is($gadget1->generateRoleId($anonymousMember), 'anonymous');
