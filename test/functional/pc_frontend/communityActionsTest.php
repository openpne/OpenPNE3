<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

// create a new test browser
$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));

$browser
  ->login('sns@example.com', 'password')

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
;
