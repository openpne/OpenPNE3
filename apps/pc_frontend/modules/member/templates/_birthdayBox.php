<?php if (!is_null($targetDay)): ?>
<?php if ($targetDay === 0): ?>
<div class="parts birthday">
<img src="<?php echo public_path('images/birthday_f.gif') ?>" alt="Happy Birthday!" />
</div>
<?php elseif (0 < $targetDay && $targetDay <= 3): ?>
<div class="parts birthday">
<img src="<?php echo public_path('images/birthday_f_2.gif') ?>" alt="" />
</div>
<?php endif ?>
<?php endif ?>
