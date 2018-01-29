<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(10, new lime_output_color());

$table = Doctrine::getTable('Application');

// ->getAddApplicationRuleChoices()
$t->diag('->getAddApplicationRuleChoices()');
$t->is($table->getAddApplicationRuleChoices(),
  array(
    0 => '禁止',
    1 => 'SNS管理者の許可が必要',
    2 => '許可',
), '->getAddApplicationRuleChoices() returns array of choices correctly');


// change language
sfContext::getInstance()->getUser()->setCulture('en_US');

// ->addApplication()
$testUrl = 'http://gist.github.com/raw/183507/ae5502d896121aebda501cbaadca55bcc1231efe/gistfile1.xsl';
$t->diag('->addApplication()');
$application = $table->addApplication($testUrl, true);
$t->isa_ok($application, 'Application', '->addApplication() returns Application object');
$t->ok(isset($application->Translation['ja_JP']), '->addApplication() fetched ja_JP application information');
$t->ok(isset($application->Translation['en_US']), '->addApplication() fetched en_US application information');

// ->getApplicationListPager()
$t->diag('->getApplicationListPager()');
$pager = $table->getApplicationListPager();
$t->isa_ok($pager, 'sfDoctrinePager', '->getApplicationListPager() returns object of sfDoctrinePager');
$t->is($pager->getNbResults(), 4, '->getApplicationListPager() returns 4 results');
$pager = $table->getApplicationListPager(1, 20, 1);
$t->isa_ok($pager, 'sfDoctrinePager', '->getApplicationListPager() returns object of sfDoctrinePager');
$t->is($pager->getNbResults(), 2, '->getApplicationListPager() returns 2 results');
$pager = $table->getApplicationListPager(1, 20, 1, false);
$t->isa_ok($pager, 'sfDoctrinePager', '->getApplicationListPager() returns object of sfDoctrinePager');
$t->is($pager->getNbResults(), 1, '->getApplicationListPager() returns 1 results');
