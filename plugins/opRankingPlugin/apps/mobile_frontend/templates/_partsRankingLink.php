<?php if (class_exists('AshiatoPeer')): ?>
<?php echo link_to(__('Access number No1 member'), 'ranking/access') ?><br>
<?php endif; ?>
<?php echo link_to(__('Member of number No1 of friends'), 'ranking/friend') ?><br>
<?php echo link_to(__('Participation number No1 community'), 'ranking/community') ?><br>
<?php if (class_exists('CommunityTopicCommentPeer')): ?>
<?php echo link_to(__('No1 community at each upsurge'), 'ranking/topic') ?>
<?php endif; ?>
