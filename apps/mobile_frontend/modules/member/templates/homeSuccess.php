<?php include_page_title(OpenPNEConfig::get('sns_name')) ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo $sf_user->getMember()->getName() ?>さん<br>
</td>

<td valign="top">
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
</td></tr>

<tr><td colspan="2">
<?php echo link_to(sprintf('ﾏｲﾌﾚﾝﾄﾞ(%s)', $sf_user->getMember()->countFriendsRelatedByMemberIdTo()), 'friend/list'); ?><br>
<?php echo link_to(sprintf('参加ｺﾐｭﾆﾃｨ(%s)', $sf_user->getMember()->countCommunityMembers()), 'community/joinlist'); ?><br>
<hr color="#0d6ddf" size="3">
</td></tr>

<tr><td colspan="2" align="center">
<?php echo link_to('ﾌﾟﾛﾌｨｰﾙ', 'member/profile') ?>
<hr color="#0d6ddf" size="3">
</td></tr>

</table>

<br>

<?php
$list = array(
  'member/editProfile' => array(
    'link' => 'ﾌﾟﾛﾌｨｰﾙ変更',
  ),
);
include_list_box('profileEdit', $list, '%link%', array('title' => 'ﾌﾟﾛﾌｨｰﾙ変更'))
?>

<?php
$list = array(
  'member/configUID' => array(
    'link' => 'かんたんﾛｸﾞｲﾝ設定',
  ),
);
include_list_box('configEdit', $list, '%link%', array('title' => '設定変更'))
?>
