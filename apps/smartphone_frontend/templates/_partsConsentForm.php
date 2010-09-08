<?php

$options->addRequiredOption('consent_from');
$options->addRequiredOption('consent_to');
$options->setDefault('allow_image_filename', 'consent_allow1.gif');

?>

<?php slot('_head'); ?>
<style type="text/css">

#consent_image {
  text-align: center;
  font-size: 1.5em;
  margin-bottom: 1em;
}

#consent_image img {
  margin: 0 1em;
}

#whoami {
  margin-bottom: 1em;
}

#whoami p {
  text-align: center;
}

</style>
<div id="consent_image">
<?php echo $options->consent_from ?>
<?php echo op_image_tag($options->allow_image_filename); ?>
<?php echo $options->consent_to ?>
</div>
<div id="whoami">
<p><strong><?php echo __('You are login to %1% as:', array('%1%' => $op_config['sns_name'])) ?></strong></p>
<p class="photo">
<?php $imgParam = array('size' => '76x76', 'alt' => $sf_user->getMember()->getName()) ?>
<?php if ($sf_user->getMember()): ?>
<?php echo op_image_tag_sf_image($sf_user->getMember()->getImageFileName(), $imgParam) ?>
<?php else: ?>
<?php echo op_image_tag('no_image.gif', $imgParam) ?>
<?php endif; ?>
</p>
<p class="text"><?php echo $sf_user->getMember()->getNameAndCount() ?></p>
</div>
<?php end_slot(); ?>

<?php
  $sf_data->getRaw('options')->body = get_slot('_head').$options->getRaw('body');
?>

<?php include_partial('global/partsYesNo', array('options' => $options)) ?>
