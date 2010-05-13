<?php $rawOptions =  $sf_data->getRaw('options') ?>
<?php
$url = $sf_request->getCurrentUri();
$options->setDefault('yes_button', __('Yes'));
$options->setDefault('no_button', __('No'));
$options->setDefault('yes_url', $url);
$options->setDefault('no_url', $url);
$options->setDefault('yes_method', 'post');
$options->setDefault('no_method', 'post');
$options->setDefault('align', '');
?>
<?php if(isset($options['body'])): ?>
<div class="block">
<?php echo $options->getRaw('body') ?>
</div>
<?php endif ?>
<div<?php if ($options['align']): ?> align="<?php echo $options['align'] ?>"<?php endif;?>>
<form action="<?php echo $options['yes_url'] ?>" method="<?php echo $options['yes_method'] ?>">
<?php $yesFomrs = ($options['yes_form'] instanceof sfForm) ? array($options['yes_form']) : $options['yes_form'] ?>
<?php if(is_array($yesFomrs)): ?>
<?php foreach($yesFomrs as $yesForm): ?>
<?php echo $yesForm->renderHiddenFields() ?>
<?php endforeach ?>
<?php else: ?>
<?php echo $rawOptions['yes_form'] ?>
<?php endif ?>
<input type="submit" class="input_submit" value="<?php echo $options['yes_button'] ?>"><br>
</form>
<form action="<?php echo $options['no_url'] ?>" method="<?php echo $options['no_method'] ?>">
<?php $noForms = ($options['no_form'] instanceof sfForm) ? array($options['no_form']) : $options['no_form'] ?>
<?php if(is_array($noForms)): ?>
<?php foreach($noForms as $noForm): ?>
<?php echo $noForm->renderHiddenFields() ?>
<?php endforeach ?>
<?php else: ?>
<?php echo $rawOptions['no_form'] ?>
<?php endif ?>
<input type="submit" class ="input_submit" value="<?php echo $options['no_button'] ?>">
</form>
</div>
