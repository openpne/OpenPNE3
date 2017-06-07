<?php

require_once(dirname(__FILE__).'/../../bootstrap/unit.php');

$_app = 'pc_frontend';
$_end = 'test';
$configuration = ProjectConfiguration::getApplicationConfiguration($_app, $_env, true);
sfContext::createInstance($configuration);
new sfDatabaseManager($configuration);

$t = new lime_test(2 + 2, new lime_output_color());

$storage = new opPDODatabaseSessionStorage(array(
  'db_table'    => 'session',
  'database'    => 'doctrine',
  'db_id_col'   => 'id',
  'db_data_col' => 'session_data',
  'db_time_col' => 'time',
));

$t->ok($storage instanceof sfStorage, 'sfPDOSessionStorage is an instance of sfStorage');
$t->ok($storage instanceof sfDatabaseSessionStorage, 'sfPDOSessionStorage is an instance of sfDatabaseSessionStorage');

$storage->sessionOpen();

// main test

$sessionId = '1';
$newSessionData = 'foo:bar:baz';
$storage->write($sessionId, $newSessionData);
$t->is($storage->read($sessionId), $newSessionData, 'session data can get data correctly');

$sessionId = '1';
$newSessionData = 'testⓉⒺⓈⓉテスト🅃🄴🅂🅃てすと';
$strConverted = 'testⓉⒺⓈⓉテスト����てすと';
$storage->write($sessionId, $newSessionData);
$t->is($storage->read($sessionId), $strConverted, 'session data using 4 byte utf characters can get data correctly');
