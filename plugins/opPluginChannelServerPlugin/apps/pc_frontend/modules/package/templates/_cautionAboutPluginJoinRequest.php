<?php if ($count): ?>
<p class="caution">
<?php echo __('You\'ve gotten %1% plugins join requests', array('%1%' => $count)) ?>
&nbsp;
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=plugin_join') ?>
</p>
<?php endif; ?>

