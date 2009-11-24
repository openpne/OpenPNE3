<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';

$browser = new opTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$browser->setMobile();

include dirname(__FILE__).'/../../bootstrap/database.php';

$browser->login('sns@example.com', 'password');
$browser->setCulture('en');

$browser->get('/')
  ->with('user')->isAuthenticated()
;
