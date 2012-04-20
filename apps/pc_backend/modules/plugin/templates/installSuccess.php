<?php slot('submenu', get_partial('submenu')) ?>
<?php slot('title', __('Install a plugin')); ?>

<?php include_partial('plugin/install', array('form' => $form)); ?>