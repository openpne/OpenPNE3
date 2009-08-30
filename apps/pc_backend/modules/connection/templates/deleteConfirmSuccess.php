<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('Delete This Application')); ?>

<p><?php echo __('Do you really delete this application?') ?></p>

<form action="<?php url_for('connection_delete', $consumer) ?>" method="post">
<?php echo $form ?>
<input type="submit" value="<?php echo __('Delete') ?>" />
</form>

