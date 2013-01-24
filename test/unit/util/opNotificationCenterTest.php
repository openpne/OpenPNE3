<?php
include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(52, new lime_output_color());

$conn = Doctrine_Core::getTable('MemberConfig')->getConnection();
$member1 = Doctrine_Core::getTable('Member')->find(1);
$member2 = Doctrine_Core::getTable('Member')->find(2);

//==============================================================================

function get_notifications(Member $member)
{
  $notificationsQuery = Doctrine_Core::getTable('MemberConfig')->createQuery()
    ->andWhere('member_id = :memberId')
    ->andWhere('name = "notification_center"');

  $config = $notificationsQuery->fetchOne(array('memberId' => $member->id));
  $notifications = unserialize($config->value);
  $config->free(true);

  return $notifications;
}

//==============================================================================
$t->diag('opNotificationCenter::notify()');

$t->info('member1 => member2 (other)');
$conn->beginTransaction();

opNotificationCenter::notify($member1, $member2, 'hogehoge');
$notifications = get_notifications($member2);
$t->ok(is_array($notifications));
$t->is(count($notifications), 1);
$t->ok(is_string($notifications[0]['id']));
$t->is($notifications[0]['body'], 'hogehoge');
$t->is($notifications[0]['member_id_from'], 1);
$t->is($notifications[0]['unread'], true);
$t->is($notifications[0]['category'], 'other');
$t->is($notifications[0]['url'], null);
$t->is($notifications[0]['icon_url'], null);

$conn->rollback();

$t->info('member1 => member2 (link, with options)');
$conn->beginTransaction();

opNotificationCenter::notify($member1, $member2, 'hogehoge', array(
  'category' => 'link',
  'url' => 'http://example.com/member/1',
  'icon_url' => 'http://example.com/images/hoge.png',
));
$notifications = get_notifications($member2);
$t->ok(is_array($notifications));
$t->is(count($notifications), 1);
$t->is($notifications[0]['body'], 'hogehoge');
$t->is($notifications[0]['member_id_from'], 1);
$t->is($notifications[0]['unread'], true);
$t->is($notifications[0]['category'], 'link');
$t->is($notifications[0]['url'], 'http://example.com/member/1');
$t->is($notifications[0]['icon_url'], 'http://example.com/images/hoge.png');

$conn->rollback();

$t->info('order check (descending)');
$conn->beginTransaction();

opNotificationCenter::notify($member1, $member2, 'notify1');
opNotificationCenter::notify($member1, $member2, 'notify2');
opNotificationCenter::notify($member1, $member2, 'notify3');
$notifications = get_notifications($member2);
$t->is(count($notifications), 3);
$t->is($notifications[0]['body'], 'notify3');
$t->is($notifications[1]['body'], 'notify2');
$t->is($notifications[2]['body'], 'notify1');

$conn->rollback();

$t->info('limit check (default, <= 20)');
$conn->beginTransaction();

foreach (range(1, 30) as $num)
{
  opNotificationCenter::notify($member1, $member2, 'notify'.$num);
}
$notifications = get_notifications($member2);
$t->is(count($notifications), 20);
$t->is($notifications[0]['body'], 'notify30');
$t->is($notifications[1]['body'], 'notify29');
$t->is($notifications[2]['body'], 'notify28');
$t->is($notifications[17]['body'], 'notify13');
$t->is($notifications[18]['body'], 'notify12');
$t->is($notifications[19]['body'], 'notify11');

$conn->rollback();

$t->info('limit check (<= 5)');
$conn->beginTransaction();

sfConfig::set('op_notification_limit', 5);

foreach (range(1, 10) as $num)
{
  opNotificationCenter::notify($member1, $member2, 'notify'.$num);
}
$notifications = get_notifications($member2);
$t->is(count($notifications), 5);
$t->is($notifications[0]['body'], 'notify10');
$t->is($notifications[1]['body'], 'notify9');
$t->is($notifications[2]['body'], 'notify8');
$t->is($notifications[3]['body'], 'notify7');
$t->is($notifications[4]['body'], 'notify6');

$conn->rollback();

//==============================================================================
$t->diag('opNotificationCenter::setRead()');
$conn->beginTransaction();

opNotificationCenter::notify($member1, $member2, 'hogehoge1');
opNotificationCenter::notify($member1, $member2, 'hogehoge2');
$notifications = get_notifications($member2);
$t->is($notifications[0]['unread'], true);
$t->is($notifications[1]['unread'], true);

opNotificationCenter::setRead($member2, $notifications[0]['id']);
$notifications = get_notifications($member2);
$t->is($notifications[0]['unread'], false);
$t->is($notifications[1]['unread'], true);

$conn->rollback();

//==============================================================================
$t->diag('opNotificationCenter::getNotifications()');

$t->info('empty value');
$conn->beginTransaction();

$notifications = opNotificationCenter::getNotifications($member2);
$t->ok(is_array($notifications));
$t->is(count($notifications), 0);

$conn->rollback();

$t->info('check format');
$conn->beginTransaction();

opNotificationCenter::notify($member1, $member2, 'hogehoge');
$notifications = opNotificationCenter::getNotifications($member2);
$t->ok(is_array($notifications));
$t->is(count($notifications), 1);
$t->ok(is_string($notifications[0]['id']));
$t->is($notifications[0]['body'], 'hogehoge');
$t->is($notifications[0]['member_id_from'], 1);
$t->is($notifications[0]['unread'], true);
$t->is($notifications[0]['category'], 'other');
$t->is($notifications[0]['url'], null);
$t->is($notifications[0]['icon_url'], null);

$conn->rollback();

$t->info('2 notifications');
$conn->beginTransaction();

opNotificationCenter::notify($member1, $member2, 'notify1');
opNotificationCenter::notify($member1, $member2, 'notify2');
$notifications = opNotificationCenter::getNotifications($member2);
$t->is(count($notifications), 2);
$t->is($notifications[0]['body'], 'notify2');
$t->is($notifications[1]['body'], 'notify1');

$conn->rollback();
