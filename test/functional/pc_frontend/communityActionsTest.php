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
