<?php 
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$user = new opTestFunctional(new opBrowser(), new lime_test(null));
$user
->info('1. Testing alien')
->info('public_flag: public')
->get('/community/2')
  ->info('1-1. Alien cannot access the community home')
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(404)
  ->end()
->info('public_flag: open')
->get('/community/3')
  ->info('1-2. Alien can access the community home')
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->with('response')->isStatusCode(200)
;

opCommunityAclBuilder::clearCache();
if (class_exists('opCommunityTopicAclBuilder'))
{
  opCommunityTopicAclBuilder::clearCache();
}
$user->login('sns4@example.com', 'password');
$user
->info('2. Testing Community Member')
->info('public_flag: public')
->get('/community/2')
  ->info('2-1. Community Member can access the community home')
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->with('response')->isStatusCode(200)
->info('public_flag: open')
->get('/community/3')
  ->info('2-2. Community Member can access the community home')
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->with('response')->isStatusCode(200)
;

opCommunityAclBuilder::clearCache();
if (class_exists('opCommunityTopicAclBuilder'))
{
  opCommunityTopicAclBuilder::clearCache();
}
$user->login('sns5@example.com', 'password');
$user
->info('3. Testing SNS Member')
->info('public_flag: public')
->get('/community/2')
  ->info('3-1. SNS Member can access the community home')
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->with('response')->isStatusCode(200)
->info('public_flag: open')
->get('/community/3')
  ->info('3-2. SNS Member can access the community home')
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->with('response')->isStatusCode(200)
;

$user->login('sns@example.com', 'password');
$user
  ->info('community/search')
  ->get('/community/search')
  ->with('html_escape')->begin()
    ->isAllEscapedData('CommunityCategory', 'name')
    ->isAllEscapedData('Community', 'name')
    ->countEscapedData(1, 'CommunityConfig', 'value', array(
      'width' => 36,
      'rows'  => 3,
    ))
  ->end()

// CSRF
  ->info('/community/edit - CSRF')
  ->post('/community/edit')
  ->checkCSRF()

  ->info('/config/communityTopicNotificationMail/1 - CSRF')
  ->post('/config/communityTopicNotificationMail/1', array('topic_notify' => array()))
  ->followRedirect()
  ->checkCSRF()

  ->info('/community/dropMember/id/1/member_id/2 - CSRF')
  ->post('/community/dropMember/id/1/member_id/2')
  ->checkCSRF()

  ->info('/community/subAdminRequest/id/1/member_id/2 - CSRF')
  ->post('/community/subAdminRequest/id/1/member_id/2', array('admin_request' => array()))
  ->checkCSRF()

  ->info('/community/removeSubAdmin/id/5/member_id/2 - CSRF')
  ->post('/community/removeSubAdmin/id/5/member_id/2')
  ->checkCSRF()

  ->info('/community/changeAdminRequest/id/1/member_id/2 - CSRF')
  ->post('/community/changeAdminRequest/id/1/member_id/2', array('admin_request' => array()))
  ->checkCSRF()

  ->info('community/delete/1 - CSRF')
  ->post('community/delete/1', array('is_delete' => 1))
  ->checkCSRF()

  ->login('sns2@example.com', 'password')
  ->info('/community/quit?id=1 - CSRF')
  ->post('/community/quit?id=1')
  ->checkCSRF()

  ->login('sns3@example.com', 'password')
  ->info('/community/join?id=1 - CSRF')
  ->post('/community/join?id=1', array('community_join' => array()))
  ->checkCSRF()

// XSS
  ->login('html1@example.com', 'password')

  ->info('/member/home - XSS')
  ->get('/member/home')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/changeAdminRequest/id/1055/member_id/1056 - XSS')
  ->get('/community/changeAdminRequest/id/1055/member_id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/changeAdminRequest/id/1055/member_id/1056 - XSS')
  ->get('/community/changeAdminRequest/id/1055/member_id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/dropMember/id/1055/member_id/1056 - XSS')
  ->get('/community/dropMember/id/1055/member_id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/community/joinlist - XSS')
  ->get('/community/joinlist')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/memberList/id/1055 - XSS')
  ->get('/community/memberList/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/community/memberManage/id/1055 - XSS')
  ->get('/community/memberManage/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/community/search - XSS')
  ->get('/community/search', array('community' => array('name' => 'Community.name')))
  ->with('html_escape')->begin()
    ->isAllEscapedData('Community', 'name')
    ->countEscapedData(1, 'CommunityConfig', 'value', array('width' => 36))
  ->end()

  ->info('/community/removeSubAdmin/id/1056/member_id/1056 - XSS')
  ->get('/community/removeSubAdmin/id/1056/member_id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/community/1055 - XSS')
  ->get('/community/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
    ->countEscapedData(1, 'CommunityConfig', 'value', array('width' => 36))
  ->end()

  ->login('html2@example.com', 'password')

  ->info('/community/quit/id/1055 - XSS')
  ->get('/community/quit/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->login('sns@example.com', 'password')

  ->info('/community/join?id=1055 - XSS')
  ->get('/community/join?id=1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Community', 'name')
  ->end()
;
