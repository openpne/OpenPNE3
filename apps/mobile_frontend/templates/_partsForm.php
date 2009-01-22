<form action="<?php echo url_for($options['url']) ?>" method="post">
<?php include_customizes($id, 'formTop') ?>
<?php $forms = ($options['form'] instanceof sfForm) ? array($options['form']): $options['form'] ?>
<?php foreach ($forms as $form): ?>
<?php echo $form ?>
<?php endforeach; ?>
<?php include_customizes($id, 'lastRow') ?>

<?php if (!empty($options['align'])): ?>
<div align="<?php echo $options['align'] ?>">
<?php else: ?>
<div>
<?php endif; ?>
<input type="submit" value="<?php echo $options['button'] ?>">
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
