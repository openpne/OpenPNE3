<?php use_helper('opUtil', 'opTimeline'); ?>
<div class="row">
<div class="gadget_header span12">最新の<?php echo $op_term['activity'] ?></div>
</div>
<div class="row">
  <div class="span12">
  <?php if (isset($createdAt) && isset($body)): ?>
  <?php echo op_format_activity_time(strtotime($createdAt)); ?> - <?php echo $body ?>
  <?php else: ?>
  (<?php echo $op_term['activity'] ?>はまだありません。)
  <?php endif; ?>
  </div>
  <div class="span3 offset9">
  <?php echo link_to('もっと見る', '@sns_timeline'); ?>
  </div>
</div>
