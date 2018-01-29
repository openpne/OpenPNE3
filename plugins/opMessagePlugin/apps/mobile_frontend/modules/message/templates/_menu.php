<?php if ($messageType !== 'receive'): ?>
<?php echo link_to(__('Inbox'), '@receiveList') ?><br>
<?php endif; ?>
<?php if ($messageType !== 'send'): ?>
<?php echo link_to(__('Sent Messages'), '@sendList') ?><br>
<?php endif; ?>
<?php if ($messageType !== 'draft'): ?>
<?php echo link_to(__('Drafts'), '@draftList') ?><br>
<?php endif; ?>
<?php if ($messageType !== 'dust'): ?>
<?php echo link_to(__('Trash'), '@dustList') ?>
<?php endif; ?>
