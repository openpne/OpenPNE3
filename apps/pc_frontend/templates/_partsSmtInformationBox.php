<?php $options->setDefault('single', true) ?>
<div class="row">
  <div class="gadget_header span12"><?php echo __('Information') ?></div>
</div>
<div class="row">
  <div class="pad12">
    <?php include_customizes($id, 'bodyTop') ?>
    <?php include_customizes('information', 'bodyTop') ?>
    <?php echo $options->getRaw('body') ?>
    <?php include_customizes('information', 'bodyBottom') ?>
    <?php include_customizes($id, 'bodyBottom') ?>
  </div>
</div>
