<?php use_helper('OpenSocial') ?>

<?php if ($isOwner) : ?>
<?php echo make_app_setting_modal_box('opensocial_modal_box') ?>
<?php endif ?>

<div class="applicationList">
<?php if (isset($memberApplications) && count($memberApplications)) : ?>
<?php if ($isOwner): ?>
<?php endif; ?>
<div id="order">
<?php foreach ($memberApplications as $memberApplication) : ?>
<?php op_include_application_information_box(
  'item_'.$memberApplication->getId(),
  $memberApplication->getApplication(),
  $memberApplication->getId(),
  $isOwner
) ?>
<?php endforeach; ?>
</div>
<?php else : ?>
<?php slot('no_app_alert') ?>
<?php echo __("You haven't the app."); ?>
<?php if ($isOwner) : ?>
 <?php echo __("The Apps can be added from %0%.", array('%0%' => link_to(__('App Gallery'), '@application_gallery'))) ?>
<?php endif; ?>
<?php end_slot(); ?>
<?php op_include_box('NoApp', get_slot('no_app_alert')) ?>
<?php endif; ?>

<?php if ($isOwner) : ?>
<?php echo sortable_element('order', array(
  'url'  => '@application_sort',
  'tag'  => 'div',
  'only' => 'sortable',
  'with' => 'Sortable.serialize("order")+"&'.urlencode($form->getCSRFFieldName()).'='.urlencode($form->getCSRFToken()).'"'
)); ?>
<div class="moreInfo">
<ul class="moreInfo">
<li>
<?php echo link_to(__('App Gallery'), '@application_gallery') ?>
<?php if ($isOwner): ?>
<?php if ($isInstallApp): ?>
<li><?php echo link_to(__('Install new App'), '@application_install') ?></li>
<?php endif; ?>
<?php if ($isInstalledApp): ?>
<li><?php echo link_to(__('Apps Installed by You'), '@application_installed_list') ?></li>
<?php endif; ?>
<?php endif; ?>
</li>
</ul>
</div>
<?php endif; ?>
</div>
