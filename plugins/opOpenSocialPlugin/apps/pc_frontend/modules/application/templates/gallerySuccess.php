<?php use_helper('OpenSocial'); ?>

<?php op_include_form('searchApplication', $searchForm, array(
  'title'  => __('Search Apps'),
  'url'    => url_for('@application_gallery'),
  'method' => 'get',
  'button' => __('Search')
))?>

<?php if (isset($pager) && $pager->getNbResults()): ?>
<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, '@application_gallery?page=%d', array('use_current_query_string' => true)) ?>
<?php end_slot() ?>
<?php include_slot('pager') ?>
<?php foreach ($pager->getResults() as $application) : ?>
<?php op_include_application_information_box(
  'item_'.$application->getId(),
  $application,
  null,
  false
)?>
<?php endforeach; ?>
<?php include_slot('pager') ?>
<?php else : ?>
<?php op_include_box('AppGalleryError', __('Your search queries did not match any Apps.'), array(
    'title' => __('Search Results')
  ))
?>
<?php endif; ?>
