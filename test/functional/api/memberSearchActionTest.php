<?php

$executeLoader = false;
$app = 'api';
include dirname(__FILE__).'/../../bootstrap/functional.php';

$task = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
$task->setConfiguration($configuration);
/*$task->run(array(), array(
  'no-confirmation' => true,
  'db'              => true,
  'and-load'        => dirname(__FILE__).'/fixtures/member_search.yml',
  'application'     => $app,
  'env'             => 'test',
));*/

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));

Doctrine::getTable('SnsConfig')->set('enable_jsonapi', true);
$key = Doctrine::getTable('Member')->find(1)->getApiKey();

$browser
  ->get('member/search.json', array('apiKey' => $key, 'target' => 'friend', 'target_id' => 1))
  ->with('request')->begin()
    ->isParameter('module', 'member')
    ->isParameter('action', 'search')
  ->end()
  ->with('response')->isStatusCode(200)
;
$json = $browser->getResponse()->getContent();
$data = json_decode($json, true);
$t = $browser->test();
$t->is($data['status'], 'success', 'status is "success".');
$t->is(count($data['data']), 3, 'returned member count is 3(including blocking_member and prefriend_member).');
$t->is($data['data'][0]['name'], 'Blocked-by-me', 'including blocking member');
$t->is($data['data'][1]['name'], 'Want-to-be-friend', 'including pre-friend member');
$t->is($data['data'][2]['name'], 'Already-friend', 'also including friend member');