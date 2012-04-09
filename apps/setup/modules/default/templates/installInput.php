<?php slot('title', __('Welcome to installation')); ?>


<form action="<?php echo url_for('default/install'); ?>" method="post">
<?php //PENDING: form layout ?>
  <table>
    <?php echo $form; ?>
</table>
<input type="submit" value="<?php echo __('Confirm', array(), 'form_install'); ?>" />
</form>