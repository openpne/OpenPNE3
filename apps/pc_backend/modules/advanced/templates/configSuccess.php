<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php
$categoryAttributes = sfConfig::get('openpne_sns_category_attribute'); 
$caption = !empty($categoryAttributes[$category]['Caption']) ? $categoryAttributes[$category]['Caption'] : $category;
?>

<h2><?php echo __('SNS設定') ?> (<?php echo __($caption) ?>)</h2>

<p><?php echo __('※「設定変更」ボタンを押すと設定が反映されます。') ?></p>

<?php if ('authentication' === $category) : ?>
<p><?php echo __('※認証に関する個別の設定は、「プラグイン設定」からおこなってください。') ?></p>
<?php endif; ?>

<form action="<?php echo url_for('sns/config?category='.$category) ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('設定変更') ?>" /></td>
</tr>
</table>
</form>
