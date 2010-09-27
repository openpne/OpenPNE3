<?php $id = 'activityBox' ?>
<?php $id .= isset($gadget) ? '_'.$gadget->getId() : '' ?>

<?php slot('activities') ?>
<?php if (isset($form)): ?>
<form id="<?php echo $id ?>_form" action="<?php echo url_for('member/updateActivity') ?>" method="post"><div class="box_form">
<?php echo $form->renderHiddenFields(), "\n" ?>
<div class="box_public_flag">
<label for="activity_data_public_flag"><?php echo __('Public flag') ?></label>
<?php echo $form['public_flag'], "\n" ?>
</div>
<div class="box_count">
<span class="note"><?php echo __('Characters left')?>: </span>
<span id="<?php echo $id ?>_count" class="count">140</span>
</div>
<div class="box_body">
<span class="inputForm"><?php echo $form['body']->render(array('id' => $id.'_activity_data_body')) ?></span>
<span class="submit"><input id="<?php echo $id ?>_submit" type="submit" value="<?php echo __('%post_activity%', array('%post_activity%' => $op_term['post_activity']->titleize())) ?>" class="submit" /></span>
</div>
</div></form>
<?php use_javascript('op_activity'); ?>
<script type="text/javascript">
new opActivity("<?php echo $id ?>", "<?php echo url_for('member/updateActivity') ?>");
</script>
<?php endif; ?>
<div class="box_list">
<ol id="<?php echo $id ?>_timeline" class="activities">
<?php foreach ($activities as $activity): ?>
<?php include_partial('default/activityRecord', array('activity' => $activity)); ?>
<?php endforeach; ?>
</ol>
</div>
<?php end_slot(); ?>

<?php $params = array(
  'title' => isset($title) ? $title : __('%activity% of %my_friend%', array(
    '%activity%' => $op_term['activity']->titleize(),
    '%my_friend%' => $op_term['my_friend']->titleize()->pluralize())
  ),
  'class' => 'activityBox homeRecentList',
) ?>
<?php if (isset($gadget)): ?>
<?php $params['moreInfo'] = array(
  link_to(__('More'), isset($moreUrl) ? $moreUrl : 'friend/showActivity'),
) ?>
<?php endif; ?>
<?php op_include_box($id, get_slot('activities'), $params) ?>
