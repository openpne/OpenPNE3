<?php if ($sf_user->getMember()->countFriendPreTo()) : ?>
<hr class="toumei" />
<p class="caution">
<?php
echo __('You\'ve gotten %1% %friend% requests', array(
  '%1%' => $sf_user->getMember()->countFriendPreTo(),
));
?>
<br />
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=friend_confirm') ?>
</p>
<?php endif; ?>
