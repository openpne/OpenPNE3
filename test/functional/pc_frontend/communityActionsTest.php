<?php 
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('sns@example.com', 'password')

  ->info('community/search - XSS')
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
