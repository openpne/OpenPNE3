<?php

// OAuth library uses split() that is deprecated function.
// The deprecated error generates invalid array key ...
$debug = false;

include(dirname(__FILE__).'/../../bootstrap/functional.php');

function _oauth_get_request_token_params(OAuthConsumer $consumer, $callbackUrl)
{
  $request = OAuthRequest::from_consumer_and_token($consumer, null, 'GET', 'http://localhost/index.php/oauth/request_token', array('oauth_callback' => $callbackUrl));
  $request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, null);

  return $request->to_postdata();
}

$info = Doctrine::getTable('OAuthConsumerInformation')->find(1055);

$consumer = new OAuthConsumer($info->getKeyString(), $info->getSecret());
$params = _oauth_get_request_token_params($consumer, 'oob');

$browser = new opTestFunctional(new opBrowser());
$browser
  ->login('html1@example.com', 'password')
  ->get('/oauth/request_token?'.$params)
;

parse_str(sfContext::getInstance()->getResponse()->getContent(), $params);

// XSS
$browser
  ->info('/oauth/authorize - XSS')
  ->get('/oauth/authorize?oauth_token='.$params['oauth_token'])

  ->with('html_escape')->begin()
    ->isAllEscapedData('OAuthConsumerInformation', 'name')
  ->end()
;
