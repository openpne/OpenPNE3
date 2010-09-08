<?php if ($communityCount): ?>
<p class="caution">
<?php echo __('You\'ve gotten %1% %community% sub-administrator requests', array('%1%' => $communityCount)) ?>
&nbsp;
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=community_sub_admin_request') ?>
</p>
<?php endif; ?>

