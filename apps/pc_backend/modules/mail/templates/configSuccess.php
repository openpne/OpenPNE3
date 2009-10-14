<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('Configuration of E-mail Notifications') ?></h2>

<?php echo $form->renderFormTag(url_for('@mail_config')); ?>
<?php echo $form->renderHiddenFields(); ?>
<?php foreach ($config as $target => $mails): ?>

<h3>
<?php if ('pc' === $target): ?>
<?php echo __('For PC E-mail Address') ?>
<?php elseif ('mobile' === $target): ?>
<?php echo __('For Mobile E-mail Address') ?>
<?php elseif ('admin' === $target): ?>
<?php echo __('For Administration E-mail Address') ?>
<?php endif; ?>
</h3>

<table>
<?php foreach ($mails as $k => $v): ?>
<?php if (isset($form[$target.'_'.$k])): ?>
<?php echo $form[$target.'_'.$k]->renderRow() ?>
<?php endif; ?>
<?php endforeach; ?>
</table>

<?php endforeach; ?>

<input type="submit" value="<?php echo __('Save') ?>">
</form>


