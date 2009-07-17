<?php echo __('Can view this page with mobile only.'); ?><br />
<?php if ($op_config['enable_pc']): ?>
<a href="<?php echo public_path('/') ?>"><?php echo __('The page for pc is here.') ?></a>
<?php endif; ?>
