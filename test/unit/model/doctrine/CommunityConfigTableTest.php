<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(3, new lime_output_color());

$table = Doctrine::getTable('CommunityConfig');

//------------------------------------------------------------
$t->diag('CommunityConfigTable');
$t->diag('CommunityConfigTable::retrievesByCommunityId()');
$configs = $table->retrievesByCommunityId(1);
$t->isa_ok($configs, 'Doctrine_Collection');
$t->is($configs->count(), 3);

//------------------------------------------------------------
$t->diag('CommunityConfigTable::retrieveByNameAndCommunityId()');
$config = $table->retrieveByNameAndCommunityId('description', 1);
$t->is($config->getValue(), 'IDが1番のコミュニティ');

