<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<?php slot('title', __('Change Language')) ?>

<form action="<?php echo url_for('admin/changeLanguage') ?>" method="post">
<?php echo $form ?>
<br />
<input type="submit" value="<?php echo __('Setting') ?>" />
</form>
