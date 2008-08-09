<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByIsDispRegist()');

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByIsDispConfig()');

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByIsDispSearch()');

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByName()');
