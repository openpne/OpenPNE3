<?php echo op_include_parts('manageList', 'manageList', array(
  'pager' => $pager,
  'pager_url'=> 'friend/manage?page=%d',
  'item_url' => 'obj_member_profile',
  'image_filename_method' => 'getImageFilename',
  'title' => __('My Friends Setting'),
  'menus' => array(
    array('text' => __('Delete from my friends.'), 'url' => 'obj_friend_unlink', 'class' => 'delete'),
  ),
)); ?>
