<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

include(dirname(__FILE__).'/../../bootstrap/unit.php');
include(dirname(__FILE__).'/../../bootstrap/database.php');
include dirname(__FILE__).'/../../bootstrap/util.php';

$t = new lime_test(1, new lime_output_color());

$blogRssCacheTable = Doctrine::getTable('BlogRssCache');

// updateByMemberId(add)
$t->diag('->updateByMemberId()');
setBlogUrl(1, BEFORE_FEED_URL);
$blogRssCacheTable->updateByMemberId(1);
$blogRssCacheList = $blogRssCacheTable->findAll();
$t->is($blogRssCacheList->count(), 2, 'count success');

// findByMemberId
$t->diag('->findByMemberId()');
$blogRssCacheList = $blogRssCacheTable->findByMemberId(1);
$t->is($blogRssCacheList[0]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_1', 'title_1 success');

// findByMember
$t->diag('->findByMember()');
$blogRssCacheList = $blogRssCacheTable->findByMember(Doctrine::getTable('Member')->find(1));
$t->is($blogRssCacheList[0]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_1', 'title_1 success');

// updateByMemberId(update)
$t->diag('->updateByMemberId()');
setBlogUrl(1, AFTER_FEED_URL);
$blogRssCacheTable->updateByMemberId(1);
$blogRssCacheList = $blogRssCacheTable->findAll();
$t->is($blogRssCacheList->count(), 4, 'count success');

// findByMemberId(update)
$t->diag('->findByMemberId()');
$blogRssCacheList = $blogRssCacheTable->findByMemberId(1);
$t->is($blogRssCacheList[0]->getTitle(), 'title_4', 'title_4 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_3', 'title_3 success');
$t->is($blogRssCacheList[2]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[3]->getTitle(), 'title_1', 'title_1 success');

// deleteByMemberId
$t->diag('->deleteByMemberId()');
$blogRssCacheTable->deleteByMemberId(1);
$blogRssCacheList = $blogRssCacheTable->findByMemberId(1);
$t->is($blogRssCacheList->count(), 0, 'count success');

// countFeedUrl
$t->diag('->countFeedUrl()');
setBlogUrl(2, BEFORE_FEED_URL);
setBlogUrl(3, BEFORE_FEED_URL);
setBlogUrl(4, BEFORE_FEED_URL);
setBlogUrl(5, BEFORE_FEED_URL);
setBlogUrl(6, BEFORE_FEED_URL);
setBlogUrl(7, BEFORE_FEED_URL);
setBlogUrl(8, BEFORE_FEED_URL);
setBlogUrl(9, BEFORE_FEED_URL);
setBlogUrl(10, BEFORE_FEED_URL);
$t->is($blogRssCacheTable->countFeedUrl(), 10, 'count success');

// update
$t->diag('->update()');
$blogRssCacheTable->update(0, 2);

$blogRssCacheList = $blogRssCacheTable->findAll();
$t->is($blogRssCacheList->count(), 6, 'count success');
$blogRssCacheList = $blogRssCacheTable->findByMemberId(1);
$t->is($blogRssCacheList->count(), 4, 'count success');
$blogRssCacheList = $blogRssCacheTable->findByMemberId(2);
$t->is($blogRssCacheList->count(), 2, 'count success');

$blogRssCacheTable->update(2, 2);

$blogRssCacheList = $blogRssCacheTable->findAll();
$t->is($blogRssCacheList->count(), 10, 'count success');
$blogRssCacheList = $blogRssCacheTable->findByMemberId(3);
$t->is($blogRssCacheList->count(), 2, 'count success');
$blogRssCacheList = $blogRssCacheTable->findByMemberId(4);
$t->is($blogRssCacheList->count(), 2, 'count success');

$blogRssCacheTable->update(2, 10);

$blogRssCacheList = $blogRssCacheTable->findAll();
$t->is($blogRssCacheList->count(), 22, 'count success');

$blogRssCacheTable->update(0, 10);
$blogRssCacheList = $blogRssCacheTable->findAll();
$t->is($blogRssCacheList->count(), 22, 'count success');

// getFriendBlogListByMemberId
$t->diag('->getFriendBlogListByMemberId()');
addFriend(1, 2);
addFriend(1, 3);
$blogRssCacheList = $blogRssCacheTable->getFriendBlogListByMemberId(1, 20);
$t->is($blogRssCacheList->count(), 4, 'count success');
$t->is($blogRssCacheList[0]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[2]->getTitle(), 'title_1', 'title_1 success');
$t->is($blogRssCacheList[3]->getTitle(), 'title_1', 'title_1 success');

$blogRssCacheList = $blogRssCacheTable->getFriendBlogListByMemberId(2, 20);
$t->is($blogRssCacheList->count(), 4, 'count success');
$t->is($blogRssCacheList[0]->getTitle(), 'title_4', 'title_4 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_3', 'title_3 success');
$t->is($blogRssCacheList[2]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[3]->getTitle(), 'title_1', 'title_1 success');

$blogRssCacheList = $blogRssCacheTable->getFriendBlogListByMemberId(2, 1);
$t->is($blogRssCacheList->count(), 1, 'count success');
$t->is($blogRssCacheList[0]->getTitle(), 'title_4', 'title_4 success');

addFriend(2, 1, true);
$blogRssCacheList = $blogRssCacheTable->getFriendBlogListByMemberId(1, 20);
$t->is($blogRssCacheList->count(), 2, 'count success');
$t->is($blogRssCacheList[0]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_1', 'title_1 success');

// getAllMembers
$t->diag('->getAllMembers()');
$blogRssCacheList = $blogRssCacheTable->getAllMembers(40);
$t->is($blogRssCacheList->count(), 22, 'count success');

$blogRssCacheList = $blogRssCacheTable->getAllMembers(5);
$t->is($blogRssCacheList->count(), 5, 'count success');
$t->is($blogRssCacheList[0]->getTitle(), 'title_4', 'title_4 success');
$t->is($blogRssCacheList[1]->getTitle(), 'title_3', 'title_3 success');
$t->is($blogRssCacheList[2]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[3]->getTitle(), 'title_2', 'title_2 success');
$t->is($blogRssCacheList[4]->getTitle(), 'title_2', 'title_2 success');
