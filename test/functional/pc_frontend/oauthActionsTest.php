<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/oauth/request_token')->

  with('request')->begin()-> isParameter('module', 'oauth')->
    isParameter('action', 'requestToken')->
  end()->

  resetCurrentException()
;
