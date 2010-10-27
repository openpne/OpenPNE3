<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('sns@example.com', 'password')

  ->info('/confirmation/friend_confirm/2 - CSRF')
  ->post('/confirmation/friend_confirm/2', array())
  ->checkCSRF()

  ->info('/confirmation/community_confirm/11 - CSRF')
  ->post('/confirmation/community_confirm/11', array())
  ->checkCSRF()

  ->login('sns2@example.com', 'password')

  ->info('/confirmation/community_admin_request/5 - CSRF')
  ->post('/confirmation/community_admin_request/5', array())
  ->checkCSRF()

  ->login('sns3@example.com', 'password')

  ->info('/confirmation/community_sub_admin_request/8 - CSRF')
  ->post('/confirmation/community_sub_admin_request/8', array())
  ->checkCSRF()
;
