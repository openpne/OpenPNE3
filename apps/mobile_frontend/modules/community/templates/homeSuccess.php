<?php op_mobile_page_title($community->getName()) ?>

<table width="100%" bgcolor="<?php echo $op_color["core_color_4"] ?>">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo image_tag_sf_image($community->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<?php if ($isAdmin) : ?>
<br><?php echo link_to(__('Edit Photo'), 'community/edit?id='.$community->getId()) ?>
<?php endif; ?>
</td>

<td valign="top">
<font color="<?php echo $op_color["core_color_19"] ?>">ID:</font><br>
<?php echo $community->getId() ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Date Created') ?>:</font><br>
<?php echo op_format_date($community->getCreatedAt(), 'D') ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Administrator') ?>:</font><br>
<?php echo link_to($community_admin->getName(), 'member/profile?id='.$community_admin->getId()) ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Community Category', array(), 'form_community') ?>:</font><br>
<?php echo $community->getCommunityCategory() ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Register poricy', array(), 'form_community') ?>:</font><br>
<?php echo __($community->getRegisterPoricy()) ?><br>
</td>
</tr>

<tr>
<td colspan="2">
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Description') ?>:</font><br>
<?php echo op_auto_link_text(nl2br($community->getConfig('description'))) ?><br>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td colspan="2">
<?php if ($isEditCommunity) : ?>
<?php echo link_to(__('Edit community'), 'community/edit?id=' . $community->getId()) ?><br>
<?php endif; ?>
<?php if (!$isAdmin) : ?>
<?php if ($isCommunityMember) : ?>
<?php echo link_to(__('Quit community'), 'community/quit?id=' . $community->getId()) ?><br>
<?php else : ?>
<?php echo link_to(__('Join community'), 'community/join?id=' . $community->getId()) ?><br>
<?php endif; ?>
<?php endif; ?>
<?php include_component('default', 'nav', array('type' => 'mobile_community', 'id' => $community->getId())) ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">

<?php include_customizes('menu', 'bottom') ?>
</td></tr>
</table>

<br>

<?php
$list = array();
foreach ($members as $member) {
  $list[] = link_to($member->getName(), 'member/profile?id='.$member->getId());
}
$option = array(
  'title' => __('Community Members'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), 'community/memberList?id='.$community->getId()),
  ),
);
if ($isAdmin)
{
  $option['moreInfo'][] = link_to(__('Manage member'), 'community/memberManage?id='.$community->getId())
;
}
op_include_list('communityMember', $list, $option);
?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_11"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="<?php echo $op_color["core_color_18"] ?>"><?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
