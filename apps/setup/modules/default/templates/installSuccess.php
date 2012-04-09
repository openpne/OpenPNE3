<?php slot('title', __('Confirm installation')); ?>

<form action="<?php echo url_for('default/install'); ?>" method="post">
<?php //PENDING: confirm layout ?>
  <table>
    <?php foreach($form as $name => $field): ?>
<?php if('_csrf_token' != $name): ?>
<tr>
<th><?php echo $form[$name]->renderLabel(); ?></th>
<td><?php echo is_array($form->getValue($name)) ? implode('<br />', $form->getValue($name, ESC_RAW)) : $form->getValue($name); ?></td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
<?php echo $confirmForm; ?>
<input type="submit" value="<?php echo __('Install', array(), 'form_install'); ?>" />
</form>