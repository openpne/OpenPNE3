<?php slot('submenu'); ?>
<?php include_partial('submenu'); ?>
<?php end_slot(); ?>

<h2><?php echo __('カテゴリ設定') ?></h2>

<?php include_partial('categoryListForm', array(
  'form'                    => $rootForm,
  'forceAllowUserCommunity' => true,
  'categories'              => $categories,
  'deleteForm'              => $deleteForm,
)) ?>
