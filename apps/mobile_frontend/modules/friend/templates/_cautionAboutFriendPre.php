<?php if ($sf_user->getMember()->countFriendPreTo()) : ?>
<font color="red">
<?php
echo __('You\'ve gotten %1% %friend% requests', array(
  '%1%' => $sf_user->getMember()->countFriendPreTo(),
));
?>
&nbsp;
<?php echo link_to(__('Go to Confirmation Page'), '@confirmation_list?category=friend_confirm') ?>
</font>
<?php endif; ?>
