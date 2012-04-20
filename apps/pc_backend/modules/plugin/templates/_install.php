<form action="<?php echo url_for('plugin/install'); ?>" method="post">
<table>
<?php echo $form; ?>
</table>
<input type="submit" value="<?php echo __('Do install'); ?>" class="input_submit" />
</form>