<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());
$member1 = Doctrine::getTable('Member')->findOneByName('A');

//------------------------------------------------------------
$t->diag('MemberImage');
$t->diag('MemberImage::createPre()');
$memberImage = $member1->getMemberImage();
$t->ok($memberImage[0]->getIsPrimary());
$t->ok(!$memberImage[1]->getIsPrimary());
