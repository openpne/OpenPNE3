<?php if (!is_null($targetDay)): ?>
<?php if ($targetDay === 0): ?>
<font color="#ff0000">
<?php echo __('Happy Birthday!') ?>
</font>
<?php elseif (0 < $targetDay && $targetDay <= 3): ?>
<font color="#ff0000">
<?php echo __('It is member\'s birthday soon.') ?>
</font>
<?php endif ?>
<?php endif ?>
