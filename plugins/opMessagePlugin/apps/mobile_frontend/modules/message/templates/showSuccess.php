<?php use_helper('opMessage') ?>
<?php op_mobile_page_title(__($title), __('Message')) ?>
<?php if ($message->getIsSender()): ?>
<?php echo __('To') ?>：
<?php foreach ($message->getMessageSendLists() as $sendTo): ?>
<?php echo op_message_link_to_member($sendTo->getMember()) ?>
<?php if (!$sendTo->getMemberId()): ?>
<?php $isDeletedMember = true; ?>
<?php endif; ?><br>
<?php endforeach; ?>
<?php else: ?>
<?php echo __('From') ?>：
<?php echo op_message_link_to_member($message->getMember()) ?>
<?php if (!$message->getMemberId()): ?>
<?php $isDeletedMember = true; ?>
<?php endif; ?><br>
<?php endif; ?>

<?php echo __('Created At') ?>：
<?php echo op_format_date($message->getCreatedAt(), 'XDateTime'); ?><br>

<?php echo __('Subject') ?>：
<?php echo $message->getSubject() ?>

<?php $images = $message->getMessageFile() ?>
<?php if (count($images)): ?>
<br>
<?php foreach ($images as $image): ?>
<?php echo link_to(__('View Image'), sf_image_path($image->getFile(), array('size' => '120x120', 'f' => 'jpg'))) ?><br>
<?php endforeach; ?>
<?php endif; ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php echo nl2br($message->getBody()) ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php if ('dust' == $messageType): ?>
<?php echo $form->renderFormTag(url_for('message/restore?id='.$deletedId)); ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Restore') ?>" />
</form>
<?php endif; ?>

<?php echo $form->renderFormTag(url_for($deleteButton)); ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Delete') ?>"  />
</form>

<?php if ('dust' != $messageType && !$message->getIsSender() && !$isDeletedMember): ?>
<br><?php echo link_to(__('Reply'), 'message/reply?id='.$message->getId()) ?>
<?php endif; ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">


<?php if ('receive' == $messageType): ?>
<?php echo link_to(__('Inbox'), '@receiveList') ?>
<?php elseif ('send' == $messageType): ?>
<?php echo link_to(__('Sent Messages'), '@sendList') ?>
<?php else : ?>
<?php echo link_to(__('Trash'), '@dustList') ?>
<?php endif; ?>
