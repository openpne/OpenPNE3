<?php if ($id !== $sf_user->getMemberId()): ?>
<?php echo link_to(__('Compose Message'), 'message/sendToFriend?id='.$id) ?><br>
<?php endif; ?>
