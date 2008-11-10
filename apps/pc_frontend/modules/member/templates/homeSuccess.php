<?php
$body = '';
if ($information) {
  $body = $sf_data->getRaw('information')->getValue();
}
include_information_box('information', $body)
?>

<?php include_box('homeTopSampleBox', 'ホーム画面', sprintf('あなたのホームです。(メンバーID:%d, ニックネーム:%s)', $sf_user->getMemberId(), $sf_user->getMember()->getName())); ?>

<ul>
<li><?php echo link_to(sprintf('フレンド一覧(%d)', $sf_user->getMember()->countFriends()), 'friend/list') ?></li>
<li><?php echo link_to(sprintf('参加コミュニティ一覧(%d)', $sf_user->getMember()->countCommunityMembers()), 'community/joinlist') ?></li>
</ul>
