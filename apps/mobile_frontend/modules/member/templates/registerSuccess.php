<?php $has_register_box = false ?>
<?php foreach ($sf_user->getAuthAdapters() as $adapter): ?>
<?php if (in_array($adapter->getAuthModeName(), $sf_user->getAuthModes()->getRawValue()) && $sf_context->getController()->componentExists($adapter->getAuthModuleName(), 'registerBox')): ?>
<?php
$has_register_box = true;
include_component($adapter->getAuthModuleName(), 'registerBox');
?>
<?php endif; ?>
<?php endforeach; ?>

<?php if (!$has_register_box): ?>
<?php echo op_include_box('noRegisterBoxError', '現在登録可能なｱｶｳﾝﾄがありません。') ?>
<?php endif; ?>
