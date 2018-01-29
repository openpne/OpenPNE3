<?php slot('op_sidemenu'); ?>
<?php include_partial('pluginInformationBar', array('package' => $package)) ?>
<?php end_slot(); ?>

<?php
$options = array(
  'title' => __('Developers'),
  'list' => $pager->getResults(),
  'link_to' => 'member/profile?id=',
  'pager' => $pager,
  'crownIds' => $package->getLeadMemberIds()->getRawValue(),
  'link_to_pager' => '@package_list_member?name='.$package->name.'&page=%d',
);
op_include_parts('photoTable', 'developerList', $options)
?>
