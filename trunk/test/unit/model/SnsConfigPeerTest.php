<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('SnsConfigPeer::retrieveByName()');
$config = SnsConfigPeer::retrieveByName('SNS_NAME');
$t->isa_ok($config, 'SnsConfig', 'retrieveByName() returns a SnsConfig');
$t->is($config->getValue(), 'MySNS', 'SnsConfig::getValue() returns a value');

$t->cmp_ok(SnsConfigPeer::retrieveByName('unknown'), '===', NULL, 'retrieveByName() returns NULL if name does not exist');
