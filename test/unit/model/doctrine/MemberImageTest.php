<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());
$member1 = Doctrine::getTable('Member')->findOneByName('A');

//------------------------------------------------------------
$t->diag('MemberImage');
$t->diag('MemberImage::createPre()');
$memberImage = $member1->getMemberImage();
$t->ok($memberImage[0]->getIsPrimary());
$t->ok(!$memberImage[1]->getIsPrimary());

$memberImage[0]->delete();
$t->ok(!Doctrine::getTable('MemberImage')->find(1), 'The related "MemberImage" record is removed.');
$t->ok(!Doctrine::getTable('File')->find(3), 'The parent "File" record is removed.');

