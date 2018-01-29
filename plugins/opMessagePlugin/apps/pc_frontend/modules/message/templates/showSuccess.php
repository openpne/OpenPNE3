<?php use_helper('Date', 'Text', 'opMessage'); ?>
<?php include_partial('message/sidemenu', array('listType' => $messageType, 'forceLink' => true)) ?>
<div class="dparts messageDetailBox">
<div class="parts">
<div class="partsHeading"><h3><?php echo __('Message') ?></h3></div>
<?php if ($previousMessage || $nextMessage): ?>
<div class="block prevNextLinkLine">
<?php if ($previousMessage): ?><p class="prev"><?php echo link_to(__('Previous', array(), 'pager'), '@read'.ucfirst($messageType).'Message?id='.$previousMessage->getId()) ?></p><?php endif; ?>
<?php if ($nextMessage): ?><p class="next"><?php echo link_to(__('Next', array(), 'pager'),'@read'.ucfirst($messageType).'Message?id='.$nextMessage->getId()) ?> </p><?php endif; ?>
</div>
<?php endif; ?>
<table>
<tr>
<?php if(count($fromOrToMembers) == 1): ?>
<td class="photo" rowspan="3"><?php echo link_to(image_tag_sf_image($fromOrToMembers[0]->getImageFileName(), array('size' => '76x76')), 'member/profile?id='.$fromOrToMembers[0]->getId()) ?></td>
<?php endif; ?>
<th>
<?php if ($message->getIsSender()): ?>
<?php echo __('To') ?>
<?php else: ?>
<?php echo __('From') ?>
<?php endif; ?></th>
<td>
<ul>
<?php foreach ($fromOrToMembers as $member): ?>
  <li><?php echo op_message_link_to_member($member) ?></li>
<?php if (!$member->getId()): ?>
<?php $isDeletedMember = true; ?>
<?php endif; ?>
<?php endforeach; ?>
</ul>
</td>
</tr>
<tr>
<th><?php echo __('Created At') ?></th>
<td><?php echo format_datetime($message->getCreatedAt(), 'f') ?></td>
</tr><tr>
<th><?php echo __('Subject') ?></th>
<td><?php echo $message->getSubject() ?></td>
</tr>
</table>
<div class="block">
<?php $images = $message->getMessageFile() ?>
<?php if (count($images)): ?>
<ul class="photo">
<?php foreach ($images as $image): ?>
<li><a href="<?php echo sf_image_path($image->getFile()) ?>" target="_blank">
<?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120')) ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<p class="text">
<?php echo auto_link_text(nl2br($message->getDecoratedMessageBody()), 'urls', array('target' => '_blank'), true, 57) ?>
</p>
</div>

<?php /* @todo 添付ファイル
({if $c_message.filename && $smarty.const.OPENPNE_USE_FILEUPLOAD})
<div class="block attachFile"><ul>
<li><a href="({t_url m=pc a=do_h_message_file_download})&amp;target_c_message_id=({$c_message.c_message_id})&amp;sessid=({$PHPSESSID})">({$c_message.original_filename})</a></li>
</ul></div>
({/if})
*/ ?>
<div class="operation">
<ul class="moreInfo button">
<?php if ($messageType == 'dust'): ?>
<li>
<?php echo $form->renderFormTag(url_for('message/restore?id='.$deletedId)); ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Restore') ?>" class="input_submit" />
</form>
</li>
<?php endif; ?>
<li>
<?php echo $form->renderFormTag(url_for($deleteButton)); ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Delete') ?>" class="input_submit" />
</form>
</li>
<?php if ($messageType != 'dust' && !$message->getIsSender() && !$isDeletedMember): ?>
<li><?php echo button_to(__('Reply'), 'message/reply?id='.$message->getId(), array('class' => 'input_submit')) ?></li>
</ul>
<?php else:?>
</ul>
<?php endif; ?>
</div>
</div>
</div>
