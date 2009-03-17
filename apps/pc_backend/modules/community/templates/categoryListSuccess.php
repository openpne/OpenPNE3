<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('コミュニティカテゴリ設定')); ?>

<h3>大カテゴリ</h3>
<?php include_partial('categoryListForm', array(
  'form'                    => $rootForm,
  'forceAllowUserCommunity' => true,
  'categories'              => $categories,
  'deleteForm'              => $deleteForm,
)) ?>

<h3>小カテゴリ</h3>
<?php foreach ($categories as $category): ?>
<h4><?php echo $category ?></h4>
<?php include_partial('categoryListForm', array(
  'form'       => $categoryForms[$category->getId()],
  'categories' => $category->getChildren(),
  'deleteForm' => $deleteForm,
)) ?>
<?php endforeach; ?>
