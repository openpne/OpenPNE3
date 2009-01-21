<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $community->getName() ?></a></font><br>
</td></tr>
<tr><td align="center" bgcolor="#DDDDDD">
<font color="#000000"><a name="top"><?php echo __('Community member') ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<center>
<?php echo pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $list[] = link_to(sprintf('%s(%d)', $member->getName(), $member->countFriends()), 'member/profile?id='.$member->getId());
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>

<?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id'), false); ?>
