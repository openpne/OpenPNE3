<?php

$executeLoader = false;
require_once __DIR__.'/../../bootstrap/functional.php';
require_once __DIR__.'/../../bootstrap/database.php';

$numberOfTests = 4;
$tester = new opTestFunctional(new opBrowser(), new lime_test($numberOfTests), array(
  'doctrine' => 'sfTesterDoctrine',
));

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
  ->info('/activity/post.json - community_id: 1')
  ->post('/activity/post.json', array(
    'apiKey' => $apiKeyMember1,
    'target' => 'community',
    'target_id' => '1',
    'body' => 'hogehoge',
  ))
  ->isStatusCode(200)
  ->with('doctrine')->check('ActivityData', array(
    'member_id' => '1',
    'foreign_table' => 'community',
    'foreign_id' => '1',
    'body' => 'hogehoge',
  ), 1)

  ->info('/activity/post.json - community_id: 2 (not participated)')
  ->post('/activity/post.json', array(
    'apiKey' => $apiKeyMember1,
    'target' => 'community',
    'target_id' => '2',
    'body' => 'hogehoge',
  ))
  ->isStatusCode(403)
  ->with('doctrine')->check('ActivityData', array(
    'member_id' => '1',
    'foreign_table' => 'community',
    'foreign_id' => '2',
    'body' => 'hogehoge',
  ), 0)
;
