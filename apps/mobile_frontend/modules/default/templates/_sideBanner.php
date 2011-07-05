<?php $member = $sf_user->getMember()->getRawValue() ?>
<?php if ($member && !($member instanceof opAnonymousMember)): ?>
<?php echo op_banner('side_after') ?>
<?php else: ?>
<?php echo op_banner('side_before') ?>
<?php endif ?>
