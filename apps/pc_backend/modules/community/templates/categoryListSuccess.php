<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('コミュニティカテゴリ設定')); ?>

<p><?php echo __('「メンバー作成コミュニティの許可」からチェックを外すと、 ID が 1 のメンバーしか、そのカテゴリ上でコミュニティの作成をおこなえなくなります。') ?></p>

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
  'categories' => $category->getNode()->getChildren(),
  'deleteForm' => $deleteForm,
)) ?>
<?php endforeach; ?>
