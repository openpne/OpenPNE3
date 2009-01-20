<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo __('Settings') ?></a></font><br>
</td></tr>
<?php if ($categoryName) : ?>
<tr><td align="center" bgcolor="#DDDDDD">
<font color="#000000"><a name="top"><?php echo $categoryCaptions[$categoryName] ?></a></font><br>
</td></tr>
<?php endif; ?>
</table>
<?php end_slot(); ?>
<?php if ($categoryName) : ?>
<form action="<?php echo url_for('member/config?category='.$categoryName) ?>" method="post">
<?php echo $form ?>
<br>
<center><input type="submit" value="<?php echo __('Save') ?>"></center>
</form>
<?php else: ?>
<?php echo __('Please select the item from the menu.'); ?>
<?php endif; ?>
