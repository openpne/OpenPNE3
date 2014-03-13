<?php if ($pager->getNbResults()): ?>
<?php
op_include_parts('photoTable', 'pluginList', array(
  'title' => __('Developing Plugin List'),
  'list' => $pager->getResults(),
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => '@package_home_id?id=',
  'pager' => $pager,
  'link_to_pager' => '@package_listMember_member?page=%d&id='.$member->id,
));
?>
<?php else: ?>
<?php op_include_box('pluginList', __('There are no plugins.'), array('title' => __('Developing Plugin List'))) ?>
<?php endif; ?>
