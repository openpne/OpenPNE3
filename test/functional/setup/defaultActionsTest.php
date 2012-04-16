<?php

$dbuser = 'root';
$dbname = 'test';
$dbhost = '127.0.0.1';
$dbpassword = '';
$dbsock = '';
$dbport = '';


if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

require_once dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

$browser = new sfTestFunctional(new sfBrowser());

$browser->info('set language to English')->get('/?sf_culture=en');

$browser->info('clean install')->get('/')

  ->with('request')->begin()
    ->isParameter('module', 'default')
    ->isParameter('action', 'install')
  ->end()

  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '/Input your server info and preferences/')
  ->end()
  

  ->setField('install[dbuser]', $dbuser)
  ->setField('install[dbpass]', $dbpassword)
  ->setField('install[dbname]', $dbname)
  ->setField('install[dbhost]', $dbhost)
  ->setField('install[dbport]', $dbport)
  ->setField('install[dbsock]', $dbsock)
  ->click('Confirm')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '/Confirm your server info and preferences/')
  ->end()
  ->click('Install')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(302)
  ->end()
;

$browser->info('invalid dbuser')->get('/')
  ->setField('install[dbuser]', 'ThisDBUserIsInvalidHahaha')
  ->setField('install[dbpass]', $dbpassword)
  ->setField('install[dbname]', $dbname)
  ->setField('install[dbhost]', $dbhost)
  ->setField('install[dbport]', $dbport)
  ->setField('install[dbsock]', $dbsock)
  ->click('Confirm')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '!/Confirm your server info and preferences/')
    ->checkElement('li', '/Specified database was unavailable./')
  ->end()
;

$browser->info('invalid dbname')->get('/')
  ->setField('install[dbuser]', $dbuser)
  ->setField('install[dbpass]', $dbpassword)
  ->setField('install[dbname]', 'ThisDBNameIsInvalidHahaha')
  ->setField('install[dbhost]', $dbhost)
  ->setField('install[dbport]', $dbport)
  ->setField('install[dbsock]', $dbsock)
  ->click('Confirm')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '!/Confirm your server info and preferences/')
    ->checkElement('li', '/Specified database was unavailable./')
  ->end()
;

$browser->info('invalid dbhost')->get('/')
  ->setField('install[dbuser]', $dbuser)
  ->setField('install[dbpass]', $dbpassword)
  ->setField('install[dbname]', $dbname)
  ->setField('install[dbhost]', 'invalid.db.host')
  ->setField('install[dbport]', $dbport)
  ->setField('install[dbsock]', $dbsock)
  ->click('Confirm')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '!/Confirm your server info and preferences/')
    ->checkElement('li', '/Specified database was unavailable./')
  ->end()
;

$browser->info('invalid dbpassword')->get('/')
  ->setField('install[dbuser]', $dbuser)
  ->setField('install[dbpass]', 'ThisDBPasswordIsInvalidHahaha')
  ->setField('install[dbname]', $dbname)
  ->setField('install[dbhost]', $dbhost)
  ->setField('install[dbport]', $dbport)
  ->setField('install[dbsock]', $dbsock)
  ->click('Confirm')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '!/Confirm your server info and preferences/')
    ->checkElement('li', '/Specified database was unavailable./')
  ->end()
;

$browser->info('csrf protection')->get('/')
  ->setField('install[dbuser]', $dbuser)
  ->setField('install[dbpass]', $dbpassword)
  ->setField('install[dbname]', $dbname)
  ->setField('install[dbhost]', $dbhost)
  ->setField('install[dbport]', $dbport)
  ->setField('install[dbsock]', $dbsock)
  ->setField('install[_csrf_token]', 'ThisTokenIsInvalid')
  ->click('Confirm')
  ->isForwardedTo('default', 'install')
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('h2', '!/Confirm your server info and preferences/')
    ->checkElement('li', '/CSRF attack detected./')
  ->end()
;
