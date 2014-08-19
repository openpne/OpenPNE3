<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';
require_once __DIR__.'/../../bootstrap/database.php';

$t = new lime_test(6);

$baseQuery = Doctrine_Core::getTable('ActivityData')->createQuery('a')
  ->whereLike('a.body', 'dummy', opDoctrineQuery::MATCH_LEFT);

// There are 8 records in total.
$t->is($baseQuery->count(), 8);

//------------------------------------------------------------------------------
$t->diag('opCursorPager [maxPerPage = 6]');

$pager = new opCursorPager($baseQuery);
$pager->setMaxPerPage(6);
$pager->fetch();

$ids = array_map(function($x){ return (int)$x->id; }, $pager->getResults());
$t->is($ids, array(1, 2, 3, 4, 5, 6), 'results: [1, 2, 3, 4, 5, 6]');

//------------------------------------------------------------------------------
$t->diag('opCursorPager [maxPerPage = 4]');

$pager = new opCursorPager($baseQuery);
$pager->setMaxPerPage(4);
$pager->fetch();

$ids = array_map(function($x){ return (int)$x->id; }, $pager->getResults());
$t->is($ids, array(1, 2, 3, 4), 'results: [1, 2, 3, 4]');

//------------------------------------------------------------------------------
$t->diag('opCursorPager [maxPerPage = 4, sinceId = 4]');

$pager = new opCursorPager($baseQuery);
$pager->setMaxPerPage(4);
$pager->setSinceId(4);
$pager->fetch();

$ids = array_map(function($x){ return (int)$x->id; }, $pager->getResults());
$t->is($ids, array(5, 6, 7, 8), 'results: [5, 6, 7, 8]');

//------------------------------------------------------------------------------
$t->diag('opCursorPager [maxPerPage = 4, maxId = 3]');

$pager = new opCursorPager($baseQuery);
$pager->setMaxPerPage(4);
$pager->setMaxId(3);
$pager->fetch();

$ids = array_map(function($x){ return (int)$x->id; }, $pager->getResults());
$t->is($ids, array(1, 2, 3), 'results: [1, 2, 3]');

//------------------------------------------------------------------------------
$t->diag('opCursorPager [maxPerPage = 4, sinceId = 3, maxId = 6]');

$pager = new opCursorPager($baseQuery);
$pager->setMaxPerPage(4);
$pager->setSinceId(3);
$pager->setMaxId(6);
$pager->fetch();

$ids = array_map(function($x){ return (int)$x->id; }, $pager->getResults());
$t->is($ids, array(4, 5, 6), 'results: [4, 5, 6]');
