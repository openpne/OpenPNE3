<?php if ($communityMembersCount): ?>
<font color="red">
<?php echo __('You\'ve gotten %1% one\'s %community% joining requests', array('%1%' => $communityMembersCount)); ?>
&nbsp;
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=community_confirm') ?>
</font><br>
<?php endif; ?>
