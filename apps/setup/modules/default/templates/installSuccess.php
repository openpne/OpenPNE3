<?php slot('title', __('Confirm your server info and preferences')); ?>

<form action="<?php echo url_for('default/install'); ?>" method="post">
<?php //PENDING: confirm layout ?>
  <table>
    <?php foreach($form as $name => $field): ?>
<?php if('_csrf_token' != $name): ?>
<tr>
<th><?php echo $form[$name]->renderLabel(); ?></th>
<td>
  <?php if('non_recreate_db' == $name): ?>
    <?php echo $form->getValue($name) ? __('Non recreate db', array(), 'form_install') : ''; ?>
  <?php else: ?>
    <?php echo is_array($form->getValue($name)) ? implode('<br />', $form->getValue($name, ESC_RAW)) : $form->getValue($name); ?>
  <?php endif; ?>
</td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</table>
<?php echo $confirmForm; ?>
<input type="submit" value="<?php echo __('Install', array(), 'form_install'); ?>" class="input_submit" onclick="return confirm('<?php echo __('Are you sure?', array(), 'form_install'); ?>');" />
<?php echo button_to(__('Back'), 'default/install', 'class=input_submit'); ?>
</form>