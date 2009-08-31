<?php

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
$user = new sfTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));

// create a test user: Mr_OpenPNE
init();
$Mr_OpenPNE = createUser('sns@example.com', $user);
$Mr_OpenPNE
  ->info('This is Mr.OpenPNE');
