<?php op_mobile_page_title($community->getName()) ?>

<table width="100%" bgcolor="<?php echo $op_color["core_color_4"] ?>">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo op_image_tag_sf_image($community->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<?php if ($isAdmin) : ?>
<br><?php echo link_to(__('Edit Photo'), 'community/configImage?id='.$community->getId()) ?>
<?php endif; ?>
</td>

<td valign="top">
<font color="<?php echo $op_color["core_color_19"] ?>">ID:</font><br>
<?php echo $community->getId() ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Date Created') ?>:</font><br>
<?php echo op_format_date($community->getCreatedAt(), 'D') ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Administrator') ?>:</font><br>
<?php echo link_to($communityAdmin->getName(), '@member_profile?id='.$communityAdmin->getId()) ?><br>
<?php  
$subAdminCaption = array();
foreach ($communitySubAdmins as $m)
{
  $subAdminCaption[] = link_to($m->getName(), '@member_profile?id='.$m->getId());
}
?>
<?php if (count($subAdminCaption)): ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Sub Administrator') ?>:</font><br>
<?php echo implode("<br>\n", $subAdminCaption) ?>
<?php endif; ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('%community% Category', array(), 'form_community') ?>:</font><br>
<?php echo $community->getCommunityCategory() ?><br>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('Register policy', array(), 'form_community') ?>:</font><br>
<?php echo __($sf_data->getRaw('community')->getRegisterPolicy()) ?><br>
</td>
</tr>

<tr>
<td colspan="2">
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo __('%community% Description', array(), 'form_community') ?>:</font><br>
<?php echo nl2br(mb_substr($community->getConfig('description'), 0, 54)) ?><br>
</td>
<tr>
<td align="right" colspan="2">
<font color="<?php echo $op_color["core_color_20"] ?>">⇒</font><?php echo link_to(__('More'), 'community/detail?id='.$community->getId()) ?><br>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td colspan="2">
<?php if ($isEditCommunity) : ?>
<?php echo link_to(__('Edit %community%'), '@community_edit?id=' . $community->getId()) ?><br>
<?php endif; ?>
<?php if (!$isAdmin) : ?>
<?php if ($isCommunityMember) : ?>
<?php echo link_to(__('Quit %community%'), '@community_quit?id=' . $community->getId()) ?><br>
<?php else : ?>
<?php if ($isCommunityPreMember) : ?>
<?php echo __('You are waiting for the participation approval by %community%\'s administrator.') ?>
<?php else: ?>
<?php echo link_to(__('Join %community%'), '@community_join?id=' . $community->getId()) ?><br>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>

<?php include_customizes('menu', 'communityTop') ?>
<?php include_component('default', 'nav', array('type' => 'mobile_community', 'id' => $community->getId())) ?>
<?php include_customizes('menu', 'communityBottom') ?>

<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">

<?php include_customizes('menu', 'bottom') ?>
</td></tr>
</table>

<br>

<?php
$list = array();
foreach ($members as $member) {
  $list[] = link_to($member->getName(), '@member_profile?id='.$member->getId());
}
$option = array(
  'title' => __('%Community% Members'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), '@community_memberList?id='.$community->getId()),
  ),
);
if ($isAdmin || $isSubAdmin)
{
  $option['moreInfo'][] = link_to(__('Manage member'), '@community_memberManage?id='.$community->getId())
;
}
op_include_list('communityMember', $list, $option);
?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('@homepage') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="<?php echo $op_color["core_color_18"] ?>"><?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
