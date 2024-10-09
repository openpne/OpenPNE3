<?php if ($communityCount): ?>
<hr class="toumei" />
<p class="caution">
<?php echo __('You\'ve gotten %1% %community% administrator taking over requests', array('%1%' => $communityCount)) ?>
<br />
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=community_admin_request') ?>
</p>
<?php endif; ?>

