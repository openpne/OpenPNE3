<?php

$executeLoader = false;
require_once __DIR__.'/../../bootstrap/functional.php';
require_once __DIR__.'/../../bootstrap/database.php';

$numberOfTests = 12;
$browser = new opBrowser();
$tester = new opTestFunctional($browser, new lime_test($numberOfTests));

$t = $tester->test();

Doctrine_Core::getTable('SnsConfig')->set('enable_jsonapi', true);
$member1 = Doctrine_Core::getTable('Member')->find(1);
$member1ApiKey = $member1->getApiKey();
$member2 = Doctrine_Core::getTable('Member')->find(2);

$testcases = array();

$testcases[] = function($t, $tester)
{
  global $browser, $member1, $member1ApiKey, $member2;

  $t->diag('/push/search.json - backward compatibility (url begins with "/")');

  opNotificationCenter::notify($member2, $member1, 'test', array(
    'url' => '/member/1',
  ));

  $browser->rawConfiguration['op_base_url'] = 'http://localhost/subdir';

  $tester
    ->get('/push/search.json', array('apiKey' => $member1ApiKey))
    ->isStatusCode(200);

  $json = $tester->getResponse()->getContent();
  $data = json_decode($json, true);

  $t->is($data['status'], 'success');
  $t->is($data['data'][0]['url'], '/member/1');
};

$testcases[] = function($t, $tester)
{
  global $browser, $member1, $member1ApiKey, $member2;

  $t->diag('/push/search.json - internal uri (not begins with "/", "@")');

  opNotificationCenter::notify($member2, $member1, 'test', array(
    'url' => 'member/1',
  ));

  $browser->rawConfiguration['op_base_url'] = 'http://localhost/subdir';

  $tester
    ->get('/push/search.json', array('apiKey' => $member1ApiKey))
    ->isStatusCode(200);

  $json = $tester->getResponse()->getContent();
  $data = json_decode($json, true);

  $t->is($data['status'], 'success');
  $t->is($data['data'][0]['url'], 'http://localhost/subdir/pc_frontend_test.php/member/1');
};

$testcases[] = function($t, $tester)
{
  global $browser, $member1, $member1ApiKey, $member2;

  $t->diag('/push/search.json - internal uri (begins with "@")');

  opNotificationCenter::notify($member2, $member1, 'test', array(
    'url' => '@obj_member_profile?id=1',
  ));

  $browser->rawConfiguration['op_base_url'] = 'http://localhost/subdir';

  $tester
    ->get('/push/search.json', array('apiKey' => $member1ApiKey))
    ->isStatusCode(200);

  $json = $tester->getResponse()->getContent();
  $data = json_decode($json, true);

  $t->is($data['status'], 'success');
  $t->is($data['data'][0]['url'], 'http://localhost/subdir/pc_frontend_test.php/member/1');
};

$testcases[] = function($t, $tester)
{
  global $browser, $member1, $member1ApiKey, $member2;

  $t->diag('/push/search.json - absolute url');

  opNotificationCenter::notify($member2, $member1, 'test', array(
    'url' => 'http://www.google.com/',
  ));

  $browser->rawConfiguration['op_base_url'] = 'http://localhost/subdir';

  $tester
    ->get('/push/search.json', array('apiKey' => $member1ApiKey))
    ->isStatusCode(200);

  $json = $tester->getResponse()->getContent();
  $data = json_decode($json, true);

  $t->is($data['status'], 'success');
  $t->is($data['data'][0]['url'], 'http://www.google.com/');
};

$conn = Doctrine_Manager::connection();

foreach ($testcases as $testcase)
{
  $conn->beginTransaction();
  $testcase($t, $tester);
  $conn->rollback();
}
