<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $member->getName() ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<?php if ($member == $sf_user->getMember()) : ?>
<font color="#ff0000">
<?php echo __('This is your page other member see.') ?><br>
<?php echo __('If you edit profile, access %1%.', array('%1%' => link_to('「'. __('Edit profile') .'」', 'member/editProfile'))) ?>
</font>
<?php endif; ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
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

<tr><td colspan="2">

<?php if (!$relation->isFriend() && !$relation->isSelf()) : ?>
<?php echo link_to(__('Makes friends'), 'friend/link?id='.$member->getId()) ?><br>
<?php endif; ?>

<?php include_component('default', 'nav', array('type' => 'mobile_community', 'id' => $member->getId())) ?>

<?php include_customizes('menu', 'bottom') ?>
</td></tr>
</table>

<br>

<?php
$list = array();
foreach ($member->getFriends(5) as $friendMember)
{
  $list[] = link_to(sprintf('%s(%d)', $friendMember->getName(), $friendMember->countFriends()), 'member/profile?id='.$friendMember->getId());
}
$option = array(
  'title' => __('Friend list'),
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>'. __('More'), 'friend/list?id='.$member->getId())
  ),
);
op_include_list('friendList', $list, $option);
?>

<?php
$list = array();
foreach ($communities as $community)
{
  $list[] = link_to($community->getName(), 'community/home?id='.$community->getId());
}
$option = array(
  'title' => __('Community list with this member'),
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>'. __('More'), 'community/joinlist?member_id='.$member->getId())
  ),
);
op_include_list('communityList', $list, $option);
?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="#eeeeee">↑ <?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
