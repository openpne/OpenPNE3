<?php include_page_title($member->getName().'さん')?>

<font color="#ff0000">
※他のﾒﾝﾊﾞｰから見たあなたのﾍﾟｰｼﾞはこのようになります。<br>
ﾌﾟﾛﾌｨｰﾙを変更する場合は<?php echo link_to('「ﾌﾟﾛﾌｨｰﾙ変更」', 'member/editProfile') ?>よりおこなえます。
</font>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
</td>
<td valign="top">
<?php foreach ($member->getProfiles() as $profile) : ?>
<font color="#999966"><?php echo $profile->getCaption() ?>:</font><br>
<?php echo $profile ?><br>
<?php endforeach; ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
</td></tr>

</table>

<br>

<?php
$list = array();
foreach ($friends as $friend) {
  $friendMember = $friend->getMemberRelatedByMemberIdTo();
  $list[] = link_to(sprintf('%s(%d)', $friendMember->getName(), $friendMember->countFriendsRelatedByMemberIdTo()), 'member/profile?id='.$friendMember->getId());
}
$options = array(
  'title' => 'ﾌﾚﾝﾄﾞﾘｽﾄ',
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>もっと見る', 'friend/list?id='.$member->getId())
  ),
);
include_list_box('profileEdit', $list, $options);
?>

<?php
$list = array();
foreach ($communities as $community) {
  $list[] = link_to($community->getName(), 'community/home?id='.$community->getId());
}
$options = array(
  'title' => '参加ｺﾐｭﾆﾃｨﾘｽﾄ',
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>もっと見る', 'community/memberList?id='.$community->getId())
  ),
);
include_list_box('profileEdit', $list, $options);
?>
