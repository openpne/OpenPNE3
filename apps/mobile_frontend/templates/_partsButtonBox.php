<?php
$url = $sf_request->getCurrentUri();
$options->setDefault('button', __('Yes'));
$options->setDefault('url', url_for($url));
$options->setDefault('method', 'post');
?>

<?php if(isset($options['body'])): ?>
<?php echo $options['body'] ?>
<?php endif; ?>
<center>
<form action="<?php echo $options['url'] ?>" method="<?php echo $options['method'] ?>">
<input type="submit" value="<?php echo $options['button'] ?>">
</form>
</center>
