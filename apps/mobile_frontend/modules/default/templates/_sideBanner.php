<?php $member = $sf_user->getMember()->getRawValue() ?>
<?php if ($member && 'opAnonymousMember' !== get_class($member)): ?>
<?php echo op_banner('side_after') ?>
<?php else: ?>
<?php echo op_banner('side_before') ?>
<?php endif ?>
