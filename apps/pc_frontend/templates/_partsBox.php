<?php include_customizes($id, 'before') ?>
<div class="dparts box"><div class="parts">

<?php if ($title) : ?>
<div class="partsHeading">
<?php include_customizes($id, 'headTop') ?>
<h3><?php echo $title ?></h3>
<?php include_customizes($id, 'headBottom') ?>
</div>
<?php endif; ?>

<div class="block">
<?php if (empty($option['form'])) : ?>
<div class="body">
<?php include_customizes($id, 'bodyTop') ?>
<?php echo $body ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
<?php endif; ?>

<?php if (isset($option['form'])) : ?>
<form action="<?php echo url_for($option['url']) ?>" method="post">
<?php include_customizes($id, 'formTop') ?>
<table>
<?php foreach ($option['form'] as $form) : ?>
<?php echo $form ?>
<?php endforeach; ?>
</table>
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

</div>

</div></div>
<?php include_customizes($id, 'after') ?>
