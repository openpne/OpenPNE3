<li class="activity">
<div class="memberImage">
<?php echo link_to(image_tag_sf_image($activity->getMember()->getImageFileName(), array('size' => '76x76')), '@obj_member_profile?id='.$activity->getMemberId()) ?>
</div>
<div class="body">
<?php if ($activity->getImages()->count()): ?>
<?php $images = $activity->getImages() ?>
<?php for ($i = 0; $i < $images->count() && $i < 3;$i++): ?>
<?php if ($images[$i]->getFileId()): ?>
<?php echo image_tag_sf_image($images[$i]->getFile(), array('size' => '76x76')) ?>
<?php else: ?>
<?php echo image_tag($images[$i]->getUri(), array('width' => 76, 'height' => 76)) ?>
<?php endif; ?>
<?php endfor; ?>
<br />
<?php endif; ?>
<?php echo link_to($activity->getMember()->getName(), '@obj_member_profile?id='.$activity->getMemberId()) ?>&nbsp;
<?php if ($activity->getUri()): ?>
<?php echo link_to($activity->getBody(), $activity->getUri()) ?>
<?php else: ?>
<?php echo op_auto_link_text($activity->getBody()) ?>
<?php endif; ?>
<div class="info">
<span class="time"><?php echo op_format_activity_time(strtotime($activity->getCreatedAt())) ?>
<?php if ($activity->getSource()): ?>
 from <?php echo link_to_if($activity->getSourceUri(), $activity->getSource(), $activity->getSourceUri()) ?>
<?php endif; ?>
</span>
<?php if ($activity->getPublicFlag() != ActivityDataTable::PUBLIC_FLAG_SNS): ?>
&nbsp;<span class="public_flag"><?php echo __('Public flag') ?> : <?php echo $activity->getPublicFlagCaption() ?></span>
<?php endif; ?>
</div>
<?php if (!isset($isOperation) || $isOperation): ?>
<div class="operation">
<?php if ($activity->getMemberId() == $sf_user->getMemberId()): ?>
<?php echo link_to(__('Delete'), 'member/deleteActivity?id='.$activity->getId()) ?>
<?php endif; ?>
</div>
<?php endif; ?>
</div>
</li>
