<?php include_page_title(opConfig::get('sns_name')) ?>

<?php
$body = '';
if ($information) {
  $body = $sf_data->getRaw('information')->getValue();
}
include_information_box('information', $body)
?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo image_tag_sf_image($sf_user->getMember()->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<br>
<?php echo $sf_user->getMember()->getName() ?>さん<br>
</td>

<td valign="top">
<?php include_customizes('invite', 'before') ?>
<?php echo link_to(__('友人を誘う'), 'member/invite') ?><br>
<?php include_customizes('invite', 'after') ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
</td></tr>

<tr><td colspan="2">
<?php echo link_to(sprintf('ﾏｲﾌﾚﾝﾄﾞ(%s)', $sf_user->getMember()->countFriends()), 'friend/list'); ?><br>
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
  link_to('ﾌﾟﾛﾌｨｰﾙ変更', 'member/editProfile'),
);
include_list_box('profileEdit', $list, array('title' => 'ﾌﾟﾛﾌｨｰﾙ変更'))
?>

<?php
$list = array();
$attributes = sfConfig::get('openpne_member_category_attribute');
foreach (sfConfig::get('openpne_member_category') as $key => $value)
{
  $title = $key;
  if (!empty($attributes[$key]['caption']))
  {
    $title = $attributes[$key]['caption'];
  }

  if (count($value))
  {
    $list[] = link_to($title, 'member/config?category='.$key);
  }
}
$list[] = link_to('かんたんﾛｸﾞｲﾝ設定', 'member/configUID');
include_list_box('configEdit', $list, array('title' => '設定変更'))
?>
