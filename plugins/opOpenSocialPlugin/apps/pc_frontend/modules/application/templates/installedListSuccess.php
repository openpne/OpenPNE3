<?php use_helper('OpenSocial') ?>

<div class="partsHeading"><h3><?php echo __('Apps Installed by You') ?></h3></div>
<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, '@application_installed_list?page=%d') ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<div class="applicationList">
<?php foreach ($pager->getResults() as $application) : ?>
<?php op_include_application_information_box(
  'item_'.$application->getId(),
  $application
) ?>
<?php endforeach; ?>
</div>
<?php include_slot('pager') ?>
