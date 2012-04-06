<?php
$app = 'api';

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$configuration = ProjectConfiguration::getApplicationConfiguration('api', 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

$numOfTest = 42;

$t = new lime_test($numOfTest, new lime_output_color());
$browser = new opBrowser();

/************************
 * Test apiKey Response (5)
 ************************/

Doctrine::getTable('SnsConfig')->set('enable_jsonapi', false);
$member1ApiKey = Doctrine::getTable('Member')->find(1)->getApiKey();

$browser->get('/activity/search.json');
$t->is($browser->getResponse()->getStatusCode(), 404, '[apiKey] JSON API is not enabled.');

Doctrine::getTable('SnsConfig')->set('enable_jsonapi', true);

$browser->get('/activity/search.json');
$t->is($browser->getResponse()->getStatusCode(), 401, '[apiKey] apiKey not specified');

$browser->get('/activity/search.json', array('apiKey' => ''));
$t->is($browser->getResponse()->getStatusCode(), 401, '[apiKey] empty apiKey');

$browser->get('/activity/search.json', array('apiKey' => 'aaaaaaaaaa'));
$t->is($browser->getResponse()->getStatusCode(), 401, '[apiKey] invalid apiKey');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey));
$t->is($browser->getResponse()->getStatusCode(), 200, '[apiKey] valid apiKey');

/****************************
 * Test activity/search.json (10)
 ***************************/

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/search.json] valid request');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'target' => 'hoge'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/search.json] invalid parameter');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'target' => 'friend'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/search.json] target => friend is valid parameter.');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'target' => 'community'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/search.json] target => community parameter requires target_id parameter.');

$browser->get('/activity/seaech.json', array('apiKey' => $member1ApiKey, 'target' => 'community', 'target_id' => '1'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/search.json] target => community is valid parameter.');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'activity_id' => ''));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/search.json] activiy_id parameter is not set.');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'activity_id' => 'hogefuga'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/search.json] activity_id => hogefuga is invalid parameter');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'activity_id' => '1'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/search.json] activity_id => 1 is valid parameter');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'member_id' => ''));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/search.json] member_id parameter is valid parameter.');

$browser->get('/activity/search.json', array('apiKey' => $member1ApiKey, 'max_id' => '', 'since_id' => ''));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/search.json] max_id and since_id parameter is valid parameter.');


/*************************
 * Test activity/post.json (7)
 *************************/

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/post.json] body parameter is required.');

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey, 'body' => 'hogefuga'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/post.json] body => hogefuga parameter should be posted.');

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey, 'body' => ' '));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/post.json] body => (space) parameter should be rejected.');

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey, 'body' => '\t'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/post.json] body => (tabs) parameter should be rejected.');

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey, 'body' => 'hogefuga', 'target' => 'hoge'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/post.json] target parameter (except: \'community\') should not be set.');

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey, 'body' => 'hogefuga', 'target' => 'community'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/post.json] target_id parameter is required.');

$browser->get('/activity/post.json', array('apiKey' => $member1ApiKey, 'body' => 'hogefuga', 'target_id' => 'community', 'target_id' => '1'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/post.json] target => community and target_id => 1 parameter should be posted.');


/*******************************
 * Test activity/delete.json (4)
 *******************************/

$browser->get('/activity/delete.json', array('apiKey' => $member1ApiKey));
$t->is($browser->getResponse()->getStatusCode(), 400, '[activity/delete.json] activity_id parameter is required.');

$browser->get('/activity/delete.json', array('apiKey' => $member1ApiKey, 'activity_id' => 1));
$t->is($browser->getResponse()->getStatusCode(), 200, '[activity/delete.json] activity_id => 1 is valid parameter.');

$browser->get('/activity/delete.json', array('apiKey' => $member1ApiKey, 'id' => 2));
$t->is($browser->getResponse()->getStatusCode(), 404, '[activity/delete.json] id => 2 parameter is invalid parameter (not found).');

$browser->get('/activity/delete.json', array('apiKey' => $member1ApiKey, 'activity_id' => 3));
$t->is($browser->getResponse()->getStatusCode(), 403, '[activity/delete.json] id => 3 activity data is not allowed to delete.');


/********************************
 * test member/search.json (5)
 ********************************/

$browser->get('/member/search.json', array('apiKey' => $member1ApiKey, 'target' => 'friend'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[member/search.json] target => friend parameter is invalid.');

$browser->get('/member/search.json', array('apiKey' => $member1ApiKey, 'target' => 'friend', 'target_id' => 1));
$t->is($browser->getResponse()->getStatusCode(), 200, '[member/search.json] target => friend and target_id parameter is valid.');

$browser->get('/member/search.json', array('apiKey' => $member1ApiKey, 'target' =>'community'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[member/search.json] target => community parameter is invalid.');

$browser->get('/member/search.json', array('apiKey' => $member1ApiKey, 'target' =>'community', 'target_id' => 1));
$t->is($browser->getResponse()->getStatusCode(), 200, '[member/search.json] target => community and target_id parameter is valid.');

$browser->get('/member/search.json', array('apiKey' => $member1ApiKey, 'keyword' => 'dummy'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[member/search.json] target => dummy parameter id valid.');


/*******************************
 * test community/search.json (9)
 *******************************/

$browser->get('/community/search.json', array('apiKey' => $member1ApiKey, 'keyword' => 'dummy'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[community/search.json] keyword => dummy parameter is valid.');

$browser->get('/community/member.json', array('apiKey' => $member1ApiKey,));
$t->is($browser->getResponse()->getStatusCode(), 400, '[community/member.json] community_id parameter is required.');

$browser->get('/community/member.json', array('apiKey' => $member1ApiKey, 'community_id' => '1'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[community/member.json] community_id => 1 parameter is valid.');

$browser->get('/community/join.json', array('apiKey' => $member1ApiKey,));
$t->is($browser->getResponse()->getStatusCode(), 400, '[community/join.json] community_id parameter is required.');

$browser->get('/community/join.json', array('apiKey' => $member1ApiKey, 'community_id' => 10000));
$t->is($browser->getResponse()->getStatusCode(), 404, '[community/join.json] community_id => 10000 does not exist.');

$browser->get('/community/join.json', array('apiKey' => $member1ApiKey, 'community_id' => 1));
$t->is($browser->getResponse()->getStatusCode(), 400, '[community/join.json] community_id => 1 is already joined.');

$browser->get('/community/join.json', array('apiKey' => $member1ApiKey, 'community_id' => 2));
$t->is($browser->getResponse()->getStatusCode(), 200, '[community/join.json] community_id => 2 parameter is valid.');

$browser->get('/community/join.json', array('apiKey' => $member1ApiKey, 'community_id' => 2, 'leave' => 'true'));
$t->is($browser->getResponse()->getStatusCode(), 200, '[community/join.json] community_id => 2 and leave => true parameter is valid.');

$browser->get('/community/join.json', array('apiKey' => $member1ApiKey, 'community_id' => 1, 'leave' => 'true'));
$t->is($browser->getResponse()->getStatusCode(), 400, '[community/join.json] This member cannot leave this community (community_id => 1)');


/*******************************
 * test push/count.json (1)
 ******************************/

$browser->get('/push/count.json', array('apiKey' => $member1ApiKey));
$t->is($browser->getResponse()->getStatusCode(), 200, '[push/count.json] valid.');

/*******************************
 * test push/search.json (1)
 ******************************/

$browser->get('/push/search.json', array('apiKey' => $member1ApiKey));
$t->is($browser->getResponse()->getStatusCode(), 200, '[push/search.json] valid.');

