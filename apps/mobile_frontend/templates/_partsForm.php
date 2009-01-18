<?php include_customizes($id, 'headTop') ?>
<?php if (isset($option['title'])) : ?>
<table id="<?php echo $id ?>" width="100%">
<tr><td bgcolor="#7ddadf">
<font color="#000000"><?php echo $option['title'] ?></font><br>
</td></tr>
<?php else : ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
</table>
<?php endif; ?>
<?php include_customizes($id, 'headBottom') ?>

<?php $option_raw = $sf_data->getRaw('option') ?>
<form action="<?php echo url_for($option_raw['url']) ?>" method="post"<?php if (!empty($option['isMultipart'])) : ?> enctype="multipart/form-data"<?php endif; ?>>
<?php include_customizes($id, 'formTop') ?>
<?php if ($option['form']  instanceof sfOutputEscaperArrayDecorator) : ?>
<?php foreach ($option['form'] as $form) : ?>
<?php echo $form ?>
<?php endforeach; ?>
<?php else : ?>
<?php echo $option['form'] ?>
<?php endif; ?>
<?php include_customizes($id, 'lastRaw') ?>

<?php if (!empty($option['align'])) : ?>
<div align="<?php echo $option['align'] ?>">
<?php else : ?>
<div>
<?php endif; ?>
<input class="input_submit" type="submit" value="<?php echo $option['button'] ?>" />
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
