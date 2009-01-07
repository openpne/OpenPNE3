<?php include_page_title($member->getName().'さん')?>

<?php if ($member == $sf_user->getMember()) : ?>
<font color="#ff0000">
※他のﾒﾝﾊﾞｰから見たあなたのﾍﾟｰｼﾞはこのようになります。<br>
ﾌﾟﾛﾌｨｰﾙを変更する場合は<?php echo link_to('「ﾌﾟﾛﾌｨｰﾙ変更」', 'member/editProfile') ?>よりおこなえます。
</font>
<?php endif; ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo image_tag_sf_image($member->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
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

<?php if (!$relation->isFriend() && !$relation->isSelf()) : ?>
<?php echo link_to('ﾌﾚﾝﾄﾞに加える', 'friend/link?id='.$member->getId()) ?><br>
<?php endif; ?>

<?php
$list = array();
foreach ($member->getFriends(5) as $friendMember)
{
  $list[] = link_to(sprintf('%s(%d)', $friendMember->getName(), $friendMember->countFriends()), 'member/profile?id='.$friendMember->getId());
}
$options = array(
  'title' => 'ﾌﾚﾝﾄﾞﾘｽﾄ',
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>もっと見る', 'friend/list?id='.$member->getId())
  ),
);
include_list_box('friendList', $list, $options);
?>

<?php
$list = array();
foreach ($communities as $community)
{
  $list[] = link_to($community->getName(), 'community/home?id='.$community->getId());
}
$options = array(
  'title' => '参加ｺﾐｭﾆﾃｨﾘｽﾄ',
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>もっと見る', 'community/joinlist?member_id='.$member->getId())
  ),
);
include_list_box('communityList', $list, $options);
?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0.ﾎｰﾑ</font></a> / <a href="#top"><font color="#eeeeee">↑上へ</font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8.下へ</font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
