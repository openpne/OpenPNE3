<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Container Configuration') ?>
<?php end_slot() ?>

<?php include_partial('bottomSubmenu') ?>
<form action="<?php echo url_for('opOpenSocialPlugin/containerConfig') ?>" method="post">
<table>
<?php echo $containerConfigForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('Modify') ?>" /></td></tr>
</table>
</form>

<?php if (Doctrine::getTable('SnsConfig')->get('is_use_outer_shindig', false)): ?>
<p>
<?php echo link_to(__('Download openpne.js'), 'opOpenSocialPlugin/generateContainerConfig') ?>
</p>
<p>
<ul>
<li><?php echo __('Copy the downloaded file to %config% directory of Shindig.', array('%config%' => '<strong>config/</strong>')) ?></li>
<li><?php echo __('If you modify the profile item, you must update this file.') ?></li>
</ul>
</p>
<?php endif ?>

