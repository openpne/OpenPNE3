<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('FriendPeer::link()');

//------------------------------------------------------------

$t->diag('FriendPeer::unlink()');

//------------------------------------------------------------

$t->diag('FriendPeer::isFriend()');

//------------------------------------------------------------

$t->diag('FriendPeer::getFriendListPager()');
