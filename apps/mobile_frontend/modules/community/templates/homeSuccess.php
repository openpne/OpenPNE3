<?php op_mobile_page_title($community->getName()) ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo image_tag_sf_image($community->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
</td>

<td valign="top">
<font color="#999966">ID:</font><br>
<?php echo $community->getId() ?><br>
<font color="#999966"><?php echo __('Date Created') ?>:</font><br>
<?php echo op_format_date($community->getCreatedAt(), 'D') ?><br>
<font color="#999966"><?php echo __('Administrator') ?>:</font><br>
<?php echo link_to($community_admin->getName(), 'member/profile?id='.$community_admin->getId()) ?><br>
<font color="#999966"><?php echo __('Community Category', array(), 'form_community') ?>:</font><br>
<?php echo $community->getCommunityCategory() ?><br>
<font color="#999966"><?php echo __('Register poricy', array(), 'form_community') ?>:</font><br>
<?php echo __($community->getRegisterPoricy()) ?><br>
</td>
</tr>

<tr>
<td colspan="2">
<font color="#999966"><?php echo __('Description') ?>:</font><br>
<?php echo op_auto_link_text(nl2br($community->getConfig('description'))) ?><br>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
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
<hr color="#0d6ddf" size="3">

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
op_include_list('communityMember', $list, $option);
?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="#eeeeee"><?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
