<?php echo op_include_box('newApplication', link_to(__('Add new application'), 'connection_new'), array('title' => __('Add new application'))); ?>

<?php if ($pager->getNbResults()) : ?>
<?php echo op_include_parts('manageList', 'manageList', array(
  'pager' => $pager,
  'pager_url'=> 'connection/list?page=%d',
  'item_url' => 'connection_show',
  'image_filename_method' => 'getImageFileName',
  'title' => __('連携済みアプリケーション一覧'),
  'menus' => array(
    array('text' => __('Edit'), 'url' => 'connection_edit'),
    array('text' => __('Delete'), 'url' => 'connection_deleteConfirm'),
    array('text' => __('Revoke Access'), 'url' => 'connection_revoke_token_confirm'),
  ),
)); ?>
<?php endif; ?>
