<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';
$_SERVER['REQUEST_URI']  = '/';
$_SERVER['HTTP_HOST']    = 'sns.example.com';
$_SERVER['SCRIPT_NAME']  = '/index.php';
$_SERVER['HTTP_REFERER'] = 'http://sns.example.com/';

sfContext::createInstance($configuration);
opToolkit::clearCache();
include_once dirname(__FILE__) . '/../model/doctrine/fixtures/TestActivityTemplateConfigHandler.php';
include_once sfConfig::get('sf_lib_dir').'/vendor/symfony/lib/helper/HelperHelper.php';
use_helper('I18N', 'Tag', 'Url', 'opUtil', 'opActivity');

$t = new lime_test(8, new lime_output_color());

$t->diag('op_activity_body_filter()');
$activity1 = new ActivityData();
$activity1->body = 'foo';

$activity2 = new ActivityData();
$activity2->template = 'test_template';
$activity2->template_param = array('%foo%' => 'bar');

$activity3 = new ActivityData();
$activity3->template = 'xxxx_template';

$activity4 = new ActivityData();
$activity4->body = 'http://www.openpne.jp';

$activity5 = new ActivityData();
$activity5->body = 'http://www.openpne.jp';
$activity5->uri = '@homepage';

$t->is(op_activity_body_filter($activity1), 'foo', 'op_activity_body_filter() returns "foo"');
$t->is(op_activity_body_filter($activity2), 'Test test A test, bar!!!', 'op_activity_body_filter() returns "Test test A test, bar!!!"');
$t->is(op_activity_body_filter($activity3), '', 'op_activity_body_filter() returns ""');
$t->is(op_activity_body_filter($activity4), '<a href="http://www.openpne.jp" target="_blank">http://www.openpne.jp</a>', 'op_activity_body_filter() returns autolinked text');
$t->is(op_activity_body_filter($activity4, false), 'http://www.openpne.jp', 'op_activity_body_filter() returns "http//www.openpne.jp"');
$t->is(op_activity_body_filter($activity5), '<a href="/index.php/">http://www.openpne.jp</a>', 'op_activity_body_filter() returns linked text by uri of ActivityData');

sfConfig::set('sf_app', 'mobile_frontend');

$t->is(op_activity_body_filter($activity4), '<a href="http://sns.example.com/proxy?url=http%3A%2F%2Fwww.openpne.jp">http://www.openpne.jp</a>', 'op_activity_body_filter() returns autolinked text');

function test_filter(sfEvent $event, $value)
{
  return '';
}

sfContext::getInstance()->getEventDispatcher()->connect('op_activity.filter_body', 'test_filter');
$t->is(op_activity_body_filter($activity1),  '', 'op_activity_body_filter() returns ""');
