<?php if (!is_null($targetDay)): ?>
<?php if ($targetDay === 0): ?>
<div class="parts birthday">
<?php if ($sf_request->hasParameter('id')): ?>
<?php $img = 'birthday_f.gif' ?>
<?php else: ?>
<?php $img = 'birthday_h.gif' ?>
<?php endif ?>
<?php echo op_image_tag($img, array('alt' => 'Happy Birthday!')) ?>
</div>
<?php else: ?>
<?php if ($sf_request->hasParameter('id') && 0 < $targetDay && $targetDay <= 3): ?>
<div class="parts birthday">
<?php echo op_image_tag('birthday_f_2.gif') ?>
</div>
<?php endif ?>
<?php endif ?>
<?php endif ?>
