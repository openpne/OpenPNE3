<?php slot('op_sidemenu'); ?>
<?php include_partial('pluginInformationBar', array('package' => $package)) ?>
<?php end_slot(); ?>

<?php
$title = __('Releases of this plugin');
?>
<?php if ($pager->getNbResults()): ?>
<div class="dparts recentList"><div class="parts">
<div class="partsHeading"><h3><?php echo $title ?></h3></div>
<?php echo op_include_pager_navigation($pager, '@package_list_release?name='.$package->name.'page=%d'); ?>
<?php foreach ($pager->getResults() as $release): ?>
<dl>
<dt><?php echo op_format_date($release->created_at, 'XDateTimeJa') ?></dt>
<dd><?php echo link_to($release->version, 'release_detail', $release) ?></dd>
</dl>
<?php endforeach; ?>
<?php echo op_include_pager_navigation($pager, '@package_list_release?name='.$package->name.'page=%d'); ?>
</div></div>
<?php else: ?>
<?php op_include_box('pluginList', __('There are no releases.'), array('title' => $title)) ?>
<?php endif; ?>
