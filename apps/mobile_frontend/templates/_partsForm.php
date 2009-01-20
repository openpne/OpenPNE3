<?php include_customizes($id, 'headTop') ?>
<?php if (isset($options['title']) && $options['title'] !== ''): ?>
<table id="<?php echo $id ?>" width="100%">
<tr><td bgcolor="#7ddadf">
<font color="#000000"><?php echo $options['title'] ?></font><br>
</td></tr>
</table>
<?php endif; ?>
<?php include_customizes($id, 'headBottom') ?>

<form action="<?php echo url_for($options['url']) ?>" method="post">
<?php include_customizes($id, 'formTop') ?>
<?php $forms = ($content instanceof sfForm) ? array($content): $content ?>
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
