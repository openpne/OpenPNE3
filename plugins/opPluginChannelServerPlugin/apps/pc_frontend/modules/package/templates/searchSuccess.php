<?php

$options = array(
  'title'    => __('Search Plugin'),
  'url'      => url_for('package_search'),
  'button'   => __('Search'),
  'moreInfo' => array(link_to(__('Create a new plugin'), '@package_new')),
  'method'   => 'get'
);

op_include_form('searchPlugin', $filters, $options);

?>

<?php if ($pager->getNbResults()): ?>

<?php
$list = array();
foreach ($pager->getResults() as $key => $plugin)
{
  $list[$key] = array();
  $list[$key][__('Plugin Name')] = $plugin->getName();
  $list[$key][__('Count of Users')] = $plugin->countUsers();
  $list[$key][__('Description')] = $plugin->getDescription();
}

$options = array(
  'title'          => __('Search Results'),
  'pager'          => $pager,
  'link_to_page'   => '@package_search?page=%d',
  'link_to_detail' => '@package_home_id?id=%d',
  'list'           => $list,
);

op_include_parts('searchResultList', 'searchPluginResult', $options);
?>
<?php else: ?>
<?php op_include_box('searchPluginResult', __('Your search queries did not match any plugin.'), array('title' => __('Search Results'))) ?>
<?php endif; ?>
