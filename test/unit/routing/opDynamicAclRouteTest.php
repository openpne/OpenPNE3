<?php

require_once __DIR__.'/../../bootstrap/unit.php';
require_once __DIR__.'/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(1);

sfContext::getInstance()->getUser()->setMemberId(1);

$t->diag('`allow_empty` option');

$route = new opDynamicAclRoute('/community/:id',
  array('module' => 'community', 'action' => 'show'), // defaults
  array('id' => '\d+'), // requirements
  array('model' => 'Community', 'type' => 'object', 'allow_empty' => true, 'privilege' => 'view')); // options

$routeContext = array('method' => 'get');
$route->bind($routeContext, $route->matchesUrl('/community/999999', $routeContext));
$t->is($route->getObject(), null, '->getObject() returns null if the object does not exist');
