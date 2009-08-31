<?php

define('MOBILE_USER_AGENT', 'KDDI-CA39 UP.Browser/6.2.0.13.1.5 (FUI) MMP/2.0');

$_SERVER['HTTP_USER_AGENT'] = MOBILE_USER_AGENT;

function init()
{
  include(dirname(__FILE__).'/../../bootstrap/functional.php');
  include(dirname(__FILE__).'/../../bootstrap/database.php');
}

function createUser($mailAddress, $user)
{
  $params = array('authMailAddress' => array(
    'mail_address' => $mailAddress,
    'password'     => 'password',
  ));

  $user->post('/member/login/authMode/MailAddress', $params);

  return $user;
}

include(dirname(__FILE__).'/../../bootstrap/functional.php');
$browser = new sfBrowser();
$browser->setHttpHeader('User-Agent', MOBILE_USER_AGENT);
$user = new sfTestFunctional($browser, new lime_test(null, new lime_output_color()));

// create a test user: Mr_OpenPNE
init();
$Mr_OpenPNE = createUser('sns@example.com', $user);
$Mr_OpenPNE
  ->info('This is Mr.OpenPNE');
