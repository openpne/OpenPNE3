<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());

$t->diag('->preSave()');
$object = new MemberApplicationSetting();
$object->member_application_id = 1;
$object->name = 'foo';
$object->save();
$t->is($object->hash, md5('1'.'application'.'foo'), 'saved hash by ->preSave()');

$object = new MemberApplicationSetting();
$object->member_application_id = 1;
$object->type = 'user';
$object->name = 'foo';
$object->save();
$t->is($object->hash, md5('1'.'user'.'foo'), 'saved hash by ->preSave()');
