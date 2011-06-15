<?php

require_once dirname(__FILE__).'/../../bootstrap/unit.php';

error_reporting(error_reporting() ^ E_STRICT ^ E_DEPRECATED);

$t = new lime_test();

$uids = array(
    'au'                => '99999999999999_ge.ezweb.ne.jp',
    'docomo'            => '0000001',
    'docomo_fallback'   => '01234567890123456788',
    'softbank'          => '11111111msimmsim',
    'softbank_fallback' => '200000000000000',
);

class myOpenPNEWebRequest extends sfOpenPNEWebRequest
{
    public function isMobile()
    {
        return true;
    }

    public function setMobile($userAgent, $headers = array())
    {
        $_SERVER['HTTP_USER_AGENT'] = $userAgent;
        foreach ($headers as $key => $header) {
            if (null === $header) {
                unset($_SERVER[$key]);
            } else {
                $_SERVER[$key] = $header;
            }
        }

        opMobileUserAgent::resetInstance();
    }
}

$dispatcher = new sfEventDispatcher();
$request = new myOpenPNEWebRequest($dispatcher);

// ---

// au (uid)
$t->info('check retrieving mobile uid for au');
$request->setMobile('KDDI-CA39 UP.Browser/6.2.0.13.1.5 (FUI) MMP/2.0', array('HTTP_X_UP_SUBNO' => $uids['au']));
$t->is($request->getMobileUID(false), md5($uids['au']), '->getMobileUID() returns a valid normal uid');
$t->is($request->getMobileFallbackUID(), false, '->getMobileFallbackUID() returns no fallback uids');

// au (no uid)
$request->setMobile('KDDI-CA39 UP.Browser/6.2.0.13.1.5 (FUI) MMP/2.0', array('HTTP_X_UP_SUBNO' => null));
$t->is($request->getMobileUID(), false, '->getMobileUID() returns no uids when it is not specified in the request');

// docomo (uid and fallback uid)
$t->info('check retrieving mobile uid for docomo');
$request->setMobile('DoCoMo/2.0 P903i(c100;TB;W24H12;ser012345678901235;icc'.$uids['docomo_fallback'].')', array('HTTP_X_DCMGUID' => $uids['docomo']));
$t->is($request->getMobileUID(false), md5($uids['docomo']), '->getMobileUID() returns a valid normal uid');
$t->is($request->getMobileFallbackUID(), md5($uids['docomo_fallback']), '->getMobileFallbackUID() returns a valid fallback uid');

// docomo (fallback uid)
$request->setMobile('DoCoMo/2.0 P903i(c100;TB;W24H12;ser012345678901235;icc'.$uids['docomo_fallback'].')', array('HTTP_X_DCMGUID' => null));
$t->is($request->getMobileUID(), md5($uids['docomo_fallback']), '->getMobileUID() returns fallback uid when uid is not specified in the request');
$t->is($request->getMobileUID(false), false, '->getMobileUID() returns no uids when uid is not specified in the request and fallback uid is not allowed');

// docomo (no uid)
$request->setMobile('DoCoMo/2.0 P903i(c100;TB;W24H12)', array('HTTP_X_DCMGUID' => null));
$t->is($request->getMobileUID(), false, '->getMobileUID() returns no uids when any uids are not specified in the request');

// softbank (uid and fallback uid)
$t->info('check retrieving mobile uid for softbank');
$request->setMobile('SoftBank/1.0/930SH/SHJ001/SN'.$uids['softbank_fallback'].' Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1', array('HTTP_X_JPHONE_UID' => $uids['softbank']));
$t->is($request->getMobileUID(false), md5($uids['softbank']), '->getMobileUID() returns a valid normal uid');
$t->is($request->getMobileFallbackUID(), md5($uids['softbank_fallback']), '->getMobileFallbackUID() returns a valid fallback uid');

// softbank (fallback uid)
$request->setMobile('SoftBank/1.0/930SH/SHJ001/SN'.$uids['softbank_fallback'].' Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1', array('HTTP_X_JPHONE_UID' => null));
$t->is($request->getMobileUID(), md5($uids['softbank_fallback']), '->getMobileUID() returns fallback uid when uid is not specified in the request');
$t->is($request->getMobileUID(false), false, '->getMobileUID() returns no uids when uid is not specified in the request and fallback uid is not allowed');

// softbank (no uid)
$request->setMobile('SoftBank/1.0/930SH/SHJ001 Browser/NetFront/3.4 Profile/MIDP-2.0 Configuration/CLDC-1.1', array('HTTP_X_JPHONE_UID' => null));
$t->is($request->getMobileUID(), false, '->getMobileUID() returns no uids when any uids are not specified in the request');
