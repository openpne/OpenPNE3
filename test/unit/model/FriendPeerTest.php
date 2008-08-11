<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('FriendPeer::link()');

$t->is(FriendPeer::isFriend(1, 3), false, 'isFriend() returns false');
FriendPeer::link(1, 3);
$t->is(FriendPeer::isFriend(1, 3), true, 'isFriend() returns true');
$t->is(FriendPeer::isFriend(3, 1), true, 'isFriend() returns true');

$message = 'link() throws exception if members are already linked';
try {
  FriendPeer::link(1, 3);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

$message = 'link() throws exception if to_member does not exist';
try {
  FriendPeer::link(999, 1);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

$message = 'link() throws exception if from_member does not exist';
try {
  FriendPeer::link(1, 999);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

$message = 'link() throws exception if both members do not exist';
try {
  FriendPeer::link(999, 999);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

//------------------------------------------------------------

$t->diag('FriendPeer::unlink()');

$t->is(FriendPeer::isFriend(1, 3), true, 'isFriend() returns true');
FriendPeer::unlink(1, 3);
$t->is(FriendPeer::isFriend(1, 3), false, 'isFriend() returns false');
$t->is(FriendPeer::isFriend(3, 1), false, 'isFriend() returns false');

$message = 'unlink() throws exception if members are not linked';
try {
  FriendPeer::unlink(1, 3);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

$message = 'unlink() throws exception if to_member does not exist';
try {
  FriendPeer::unlink(999, 1);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

$message = 'unlink() throws exception if from_member does not exist';
try {
  FriendPeer::unlink(1, 999);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

$message = 'unlink() throws exception if both members do not exist';
try {
  FriendPeer::unlink(999, 999);
  $t->fail($message);
}
catch (Exception $e)
{
  $t->pass($message);
}

//------------------------------------------------------------

$t->diag('FriendPeer::isFriend()');
$t->cmp_ok(FriendPeer::isFriend(1, 2), '===', true, 'isFriend() returns true');
$t->cmp_ok(FriendPeer::isFriend(2, 3), '===', false, 'isFriend() returns false');
$t->cmp_ok(FriendPeer::isFriend(999, 1), '===', false, 'isFriend() returns false');
$t->cmp_ok(FriendPeer::isFriend(1, 999), '===', false, 'isFriend() returns false');
$t->cmp_ok(FriendPeer::isFriend(999, 999), '===', false, 'isFriend() returns false');

//------------------------------------------------------------

$t->diag('FriendPeer::getFriendListPager()');
$t->isa_ok(FriendPeer::getFriendListPager(1), 'sfPropelPager', 'getFriendListPager() returns a sfPropelPager');
