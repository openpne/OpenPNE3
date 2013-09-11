<?php

$executeLoader = false;
include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$numOfTests = 6;
$tester = new opTestFunctional(
  new opBrowser(),
  new lime_test($numOfTests, new lime_output_color())
);

$t = $tester->test();

Doctrine_Core::getTable('SnsConfig')->set('enable_jsonapi', true);
$apiKeyMember1 = Doctrine_Core::getTable('Member')->find(1)->getApiKey();

if (in_array('opTimelinePlugin', ProjectConfiguration::getActive()->getPlugins()))
{
  // opTimelinePlugin breaks standard JSON APIs (activity/*.json)
  $tester->test()->fail('unable to run tests if opTimelinePlugin is installed');
  return;
}

$tester
  ->postWithFiles(
    '/activity/post.json',
    array('apiKey' => $apiKeyMember1, 'body' => 'hogehoge'),
    array('images[0]' => dirname(__FILE__).'/uploads/dot.gif')
  )
  ->with('response')->isStatusCode(200);

$response = json_decode($tester->getResponse()->getContent(), true);
$t->is($response['status'], 'success');

$activityImage = Doctrine_Core::getTable('ActivityImage')->findByActivityDataId($response['data']['id']);
$t->is(count($activityImage), 1);
$t->is($response['data']['image_url'], 'http://localhost/cache/img/gif/w48_h48/'.$activityImage[0]->File->name.'.gif');
$t->is($response['data']['image_large_url'], 'http://localhost/cache/img/gif/w_h/'.$activityImage[0]->File->name.'.gif');

$tester
  ->postWithFiles(
    '/activity/post.json',
    array('apiKey' => $apiKeyMember1, 'body' => 'hogehoge'),
    array('images[0]' => dirname(__FILE__).'/uploads/plaintext.txt')
  )
  ->with('response')->isStatusCode(400);
