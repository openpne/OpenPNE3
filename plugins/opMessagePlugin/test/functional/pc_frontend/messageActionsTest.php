<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('sns@example.com', 'password')
  ->isStatusCode(302)

// CSRF
  ->info('/message/receiveList - CSRF')
  ->post('/message/receiveList')
  ->checkCSRF()

  ->info('/message/sendList - CSRF')
  ->post('/message/sendList')
  ->checkCSRF()

  ->info('/message/draftList - CSRF')
  ->post('/message/draftList')
  ->checkCSRF()

  ->info('/message/dustList - CSRF')
  ->post('/message/dustList')
  ->checkCSRF()

  ->info('/message/sendToFriend/id/1 - CSRF')
  ->post('/message/sendToFriend/id/1')
  ->checkCSRF()

  ->info('/message/reply/id/2 - CSRF')
  ->post('/message/reply/id/2')
  ->checkCSRF()

// XSS
  ->info('/message/receiveList - XSS')
  ->get('/message/receiveList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
  ->end()

  ->info('/message/sendList - XSS')
  ->get('/message/sendList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
  ->end()

  ->info('/message/draftList - XSS')
  ->get('/message/draftList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
  ->end()

  ->info('/message/dustList - XSS')
  ->get('/message/dustList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
  ->end()

  ->info('/message/read/2 - XSS')
  ->get('/message/read/2')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
    ->isAllEscapedData('SendMessageData', 'body')
  ->end()

  ->info('/message/check/1 - XSS')
  ->get('/message/check/1')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
    ->isAllEscapedData('SendMessageData', 'body')
  ->end()

  ->info('/message/checkDelete/3 - XSS')
  ->get('/message/checkDelete/3')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('SendMessageData', 'subject')
    ->isAllEscapedData('SendMessageData', 'body')
  ->end()

  ->info('/message/sendToFriend/id/2 - XSS')
  ->get('/message/sendToFriend/id/2')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/message/reply/id/2 - XSS')
  ->get('/message/reply/id/2')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()
;
