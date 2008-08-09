<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('MemberProfilePeer::getProfileListByMemberId()');

//------------------------------------------------------------

$t->diag('MemberProfilePeer::retrieveByMemberIdAndProfileId()');

//------------------------------------------------------------

$t->diag('MemberProfilePeer::retrieveByMemberIdAndProfileName()');
