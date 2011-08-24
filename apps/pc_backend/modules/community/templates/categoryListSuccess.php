<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php use_helper('Javascript'); ?>

<?php slot('title', __('%Community% Category Configuration')); ?>

<p><?php echo __('If you uncheck "Is allow Member %community%", only the member who has id 1 can make %community% of the category.') ?></p>

<h3><?php echo __('Big Category') ?></h3>
<?php include_partial('categoryListForm', array(
  'type'                    => 'big',
  'form'                    => $rootForm,
  'forceAllowUserCommunity' => true,
  'categories'              => $categories,
  'deleteForm'              => $deleteForm,
)) ?>

<h3><?php echo __('Small Category') ?></h3>
<?php foreach ($categories as $category): ?>
<h4><?php echo $category ?></h4>
<?php include_partial('categoryListForm', array(
  'type'       => 'small'.$category->getId(),
  'form'       => $categoryForms[$category->getId()],
  'categories' => $category->getChildren(),
  'deleteForm' => $deleteForm,
)) ?>
<?php endforeach; ?>
