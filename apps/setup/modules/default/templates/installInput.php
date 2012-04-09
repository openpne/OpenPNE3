<?php slot('title', __('Input your server info and preferences')); ?>


<form action="<?php echo url_for('default/install'); ?>" method="post">
<?php //PENDING: form layout ?>
  <table>
    <?php echo $form; ?>
</table>
<input type="submit" value="<?php echo __('Confirm', array(), 'form_install'); ?>" class="input_submit" />
</form>