<?php echo op_include_box('newApplication', link_to(__('Add new application'), 'connection/register'), array('title' => __('Add new application'))); ?>

<?php echo op_include_parts('manageList', 'manageList', array(
  'pager' => $pager,
  'pager_url'=> 'connection/list?page=%d',
  'item_url' => 'connection_show',
  'image_filename_method' => 'getImageFileName',
  'title' => __('連携済みアプリケーション一覧'),
  'menus' => array(
    array('text' => __('Edit'), 'url' => 'connection_edit'),
  ),
)); ?>
