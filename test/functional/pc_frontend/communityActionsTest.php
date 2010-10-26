<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
echo $browser
  ->info('Login')
  ->login('sns@example.com', 'password')

  ->info('/community/edit - CSRF')
  ->post('/community/edit', array())
  ->checkCSRF()

  ->info('/config/communityTopicNotificationMail/1 - CSRF')
  ->post('/config/communityTopicNotificationMail/1', array())
  ->checkCSRF()

  ->info('/community/dropMember/id/1/member_id/2 - CSRF')
  ->post('/community/dropMember/id/1/member_id/2', array())
  ->checkCSRF()

  ->info('/community/subAdminRequest/id/1/member_id/2 - CSRF')
  ->post('/community/subAdminRequest/id/1/member_id/2', array())
  ->checkCSRF()

  ->info('/community/changeAdminRequest/id/1/member_id/2 - CSRF')
  ->post('/community/changeAdminRequest/id/1/member_id/2', array())
  ->checkCSRF()

  ->info('community/delete/1 - CSRF')
  ->post('community/delete/1', array())
  ->checkCSRF()

  ->login('sns2@example.com', 'password')
  ->info('/community/quit?id=1 - CSRF')
  ->post('/community/quit?id=1', array())
  ->checkCSRF()

  ->login('sns3@example.com', 'password')
  ->info('/community/join?id=1 - CSRF')
  ->post('/community/join?id=1', array())
  ->checkCSRF()
;
