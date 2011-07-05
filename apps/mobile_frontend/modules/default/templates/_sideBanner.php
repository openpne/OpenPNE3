<?php $member = $sf_user->getMember() ?>
<?php if ($member && !($member->getRawValue() instanceof opAnonymousMember)): ?>
<?php echo op_banner('side_after') ?>
<?php else: ?>
<?php echo op_banner('side_before') ?>
<?php endif ?>
