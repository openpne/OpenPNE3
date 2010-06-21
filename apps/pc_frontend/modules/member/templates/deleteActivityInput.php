<?php slot('activity') ?>
<div class="box_list">
<ol class="activities">
<?php include_partial('default/activityRecord', array('activity' => $activity, 'isOperation' => false)) ?>
</ol>
</div>
<?php end_slot() ?>

<?php op_include_parts('yesNo', 'delete_activity', array(
  'body' => get_slot('activity'),
  'yes_form' => new BaseForm(),
  'no_method' => 'get',
  'no_url' => url_for('friend/showActivity'),
  'title' => __('Do you delete this %activity%?'),
)) ?>
