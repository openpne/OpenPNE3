<?php if (empty($option['form'])) : ?>
<div class="body">
<?php include_customizes($id, 'bodyTop') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
<?php endif; ?>

<?php if (isset($option['form'])) : ?>
<?php if (!empty($body)) : ?>
<div class="body">
<?php include_customizes($id, 'bodyTop') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
<?php endif; ?>
<?php $option_raw = $sf_data->getRaw('option') ?>
<form action="<?php echo url_for($option_raw['url']) ?>" method="post"<?php if (!empty($option['isMultipart'])) : ?> enctype="multipart/form-data"<?php endif; ?>>
<?php include_customizes($id, 'formTop') ?>
<table>
<?php if ($option['form']  instanceof sfOutputEscaperArrayDecorator) : ?>
<?php foreach ($option['form'] as $form) : ?>
<?php echo $form ?>
<?php endforeach; ?>
<?php else : ?>
<?php echo $option['form'] ?>
<?php endif; ?>
<?php include_customizes($id, 'lastRaw') ?>
</table>
<?php if (!empty($option['moreInfo'])) : ?>
<div class="block moreInfo">
<ul class="moreInfo">
<?php foreach ($option['moreInfo'] as $key => $value) : ?>
<li><?php echo $option['moreInfo']->getRaw($key); ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<div class="operation">
<ul class="moreInfo button">
<li>
<input class="input_submit" type="submit" value="<?php echo $option['button'] ?>" />
</li>
</ul>
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
<?php endif; ?>
