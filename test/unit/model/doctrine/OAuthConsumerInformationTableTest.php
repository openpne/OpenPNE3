<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(5, new lime_output_color());

$table = Doctrine::getTable('OAuthConsumerInformation');

//------------------------------------------------------------
$t->diag('OAuthConsumerInformationTable');
$t->diag('OAuthConsumerInformationTable::findByKeyString()');
$oauthConsumer1 = $table->findOneByName('test1');
$result = $table->findByKeyString($oauthConsumer1->getKeyString());
$t->isa_ok($result, 'OAuthConsumerInformation');

//------------------------------------------------------------
$t->diag('OAuthConsumerInformationTable::getListPager()');
$result = $table->getListPager();
$t->isa_ok($result, 'sfDoctrinePager');
$t->todo('is($result->getNbResults(), 2)');

$result = $table->getListPager(1);
$t->isa_ok($result, 'sfDoctrinePager');
$t->todo('is($result->getNbResults(), 1)');
