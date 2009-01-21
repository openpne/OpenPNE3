<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $member->getName() ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>
<center>
<?php echo pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $community) {
  $list[] = link_to(sprintf('%s(%d)', $community->getName(), $community->countCommunityMembers()), 'community/home?id='.$community->getId());
}
$option = array(
  'border' => true,
);
op_include_list('communityList', $list, $option);
?>

<?php echo pager_navigation($pager, 'community/joinlist?page=%d&member_id=' . $sf_params->get('member_id'), false); ?>
