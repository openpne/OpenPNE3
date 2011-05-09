<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new sfBrowser());

$browser->
  get('/connection')->

  with('request')->begin()->
    isParameter('module', 'connection')->
    isParameter('action', 'list')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '!/This is a temporary page/')->
  end()
;
