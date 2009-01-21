<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $community->getName() ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
</td>

<td valign="top">
<font color="#999966">ID:</font><br>
<?php echo $community->getId() ?><br>
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
foreach ($community->getCommunityMembers() as $communityMember) {
  $member = $communityMember->getMember();
  $list[] = link_to($member->getName(), 'member/profile?id='.$member->getId());
}
$option = array(
  'title' => __('Community member'),
  'border' => true,
  'moreInfo' => array(
    link_to('<font color="#0c5f0f">⇒</font>'. __('More'), 'community/memberList?id='.$community->getId()),
  ),
);
op_include_list('communityMember', $list, $option);
?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="#eeeeee">↑ <?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
