<?php slot('submenu', get_partial('submenu')) ?>
<?php slot('title', __('Uninstall a plugin')); ?>

<p><?php echo __('Are you sure to uninstall %plugin% ?', array('%plugin%' => $name)); ?></p>

<form action="<?php echo url_for('plugin/uninstall?name='.$name); ?>" method="post">
<?php echo $form; ?>

<input type="submit" value="<?php echo __('Uninstall'); ?>" class="input_submit" />
<?php echo button_to(__('Back'), 'plugin/list', 'class=input_submit'); ?>
</form>