<?php if (!is_null($targetDay)): ?>
<?php if ($targetDay === 0): ?>
<div class="parts birthday">
<?php if ($sf_request->hasParameter('id')): ?>
<?php $img = public_path('images/birthday_f.gif') ?>
<?php else: ?>
<?php $img = public_path('images/birthday_h.gif') ?>
<?php endif ?>
<img src="<?php echo $img ?>" alt="Happy Birthday!" />
</div>
<?php else: ?>
<?php if ($sf_request->hasParameter('id') && 0 < $targetDay && $targetDay <= 3): ?>
<div class="parts birthday">
<img src="<?php echo public_path('images/birthday_f_2.gif') ?>" alt="" />
</div>
<?php endif ?>
<?php endif ?>
<?php endif ?>
