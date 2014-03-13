<?php if ($unreadMessageCount > 0): ?>
<?php echo link_to(__('There are new %d messages!', array('%d' => $unreadMessageCount)), '@receiveList') ?><br>
<?php endif; ?>
