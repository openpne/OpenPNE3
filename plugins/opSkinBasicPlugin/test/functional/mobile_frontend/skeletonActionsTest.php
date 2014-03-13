<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';

$browser = new opTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$browser->setMobile();

include dirname(__FILE__).'/../../bootstrap/database.php';

$browser->login('defyasdf@gmail.com', 'asdfasdf');
$browser->setCulture('zh_CN');

$browser->get('/')
  ->with('user')->isAuthenticated()
;
