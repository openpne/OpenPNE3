<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('App Configuration') ?>
<?php end_slot() ?>

<?php include_partial('bottomSubmenu') ?>

<form action="<?php echo url_for('opOpenSocialPlugin/applicationConfig') ?>" method="post">
<table>
<?php echo $applicationConfigForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('Modify') ?>" /></td></tr>
</table>
</form>

