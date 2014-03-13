<?php echo __('%1% sent you %friend% link request message.', array('%1%' => $fromMember->getName())) ?>


<?php if ($message): ?>
<?php echo __('Message') ?>:
<?php echo $message ?>
<?php endif; ?>


<?php echo __('Please allow or reject this request in the confirmation list page.') ?>

<?php echo url_for('@confirmation_list?category=friend_confirm', true) ?>
