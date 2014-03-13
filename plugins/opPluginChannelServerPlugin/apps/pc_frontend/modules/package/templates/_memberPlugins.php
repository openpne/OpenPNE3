<?php
$options = array(
  'title' => __('Developing Plugin List'),
  'list' => $plugins,
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => '@package_home_id?id=',
  'moreInfo' => array(link_to(__('Show all'), '@package_listMember?id='.$member->id)),
  'row' => $row,
  'col' => $col,
);
op_include_parts('nineTable', 'pluginList_'.$gadget->getId(), $options);
