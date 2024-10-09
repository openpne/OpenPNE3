<?php if ($communityMembersCount): ?>
<hr class="toumei" />
<p class="caution">
<?php echo __('You\'ve gotten %1% one\'s %community% joining requests', array('%1%' => $communityMembersCount)); ?>
<br />
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=community_confirm') ?>
</p>
<?php endif; ?>
