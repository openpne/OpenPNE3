<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(10, new lime_output_color());

$gadget1 = Doctrine::getTable('Gadget')->find(1);
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
$t->is($gadget1->getComponentAction(), 'searchBox');
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
