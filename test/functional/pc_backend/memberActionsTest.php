<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$filePath = sfConfig::get('sf_web_dir').'/images/test.png';
$fileParams = array('member_image' => array('file' => array('name' => $filePath, 'type' => 'image/png')));

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
echo $browser
  ->info('Login')
  ->get('/')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))

  ->info('/member/delete/id/2 - CSRF')
  ->post('/member/delete/id/2', array())
  ->checkCSRF()

  ->info('/member/reject/id/2 - CSRF')
  ->post('/member/reject/id/2', array())
  ->checkCSRF()

  ->info('/member/reissuePassword/id/2 - CSRF')
  ->post('/member/reissuePassword/id/2', array())
  ->checkCSRF()

  ->info('/member/blacklist/uid - CSRF')
  ->post('/member/blacklist/uid', array())
  ->checkCSRF()

  ->info('/member/invite - CSRF')
  ->post('/member/invite', array())
  ->checkCSRF()
;
