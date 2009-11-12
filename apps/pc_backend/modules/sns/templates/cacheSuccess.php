<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('Cache Clear') ?></h2>

<p><?php echo __('If something is wrong with this site, it may be fixed by your clicking the following button to clear caches.'); ?></p>
<p><?php echo __('A response from this site will be transiently-lowered during caches are clearing.'); ?></p>

<form action="<?php echo url_for('sns/cache') ?>" method="post">
<?php echo $form ?>
<input type="submit" value="<?php echo __('Cache Clear') ?>" />
</form>

