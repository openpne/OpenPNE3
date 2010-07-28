<?php
$url = $sf_request->getCurrentUri();
$options->setDefault('button', __('Yes'));
$options->setDefault('url', url_for($url));
$options->setDefault('method', 'post');
?>
<?php if(isset($options['body'])): ?>
<div class="block">
<?php echo $options['body'] ?>
</div>
<?php endif ?>

<div class="operation">
<ul class="moreInfo button">
<li>
<form action="<?php echo $options['url'] ?>" method="<?php echo $options['method'] ?>">
<input type="submit" class="input_submit" value="<?php echo $options['button'] ?>" />
</form>
</li>
</ul>
</div>
