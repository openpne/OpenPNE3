<?php use_helper('Javascript') ?>
<?php $id = 'activityBox' ?>
<?php $id .= isset($gadget) ? '_'.$gadget->getId() : '' ?>

<?php slot('activities') ?>
<?php if (isset($form)): ?>
<form id="<?php echo $id ?>_form" action="" method="post"><div class="box_form">
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
<span class="submit"><input id="<?php echo $id ?>_submit" type="submit" value="<?php echo __('Post Activity') ?>" class="submit" /></span>
</div>
</div></form>
<script type="text/javascript">
(function (){
$('<?php echo $id ?>_form').observe('submit', function(e) {
  e.stop();
  var value = this.<?php echo $id ?>_activity_data_body.value;
  if (value && value.length > 0 && value.length <= 140) {
    request = new Ajax.Request('<?php echo url_for('member/updateActivity') ?>',
      {method: 'post', parameters: this.serialize(), onSuccess: function(obj){
        tl_obj = $('<?php echo $id ?>_timeline');
        tl_obj.innerHTML = obj.responseText + tl_obj.innerHTML;
      }}
    );
    this.reset();
    $('<?php echo $id ?>_activity_data_body').onkeyup();
  }
});
$('<?php echo $id ?>_activity_data_body').onkeyup = function() {
  var count = this.value.length;
  $('<?php echo $id ?>_count').innerHTML = 140 - count;
};
$('<?php echo $id ?>_activity_data_body').onkeyup();
})();
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
  'title' => isset($title) ? $title : __('Activities of %my_friend%', array('%my_friend%' => $op_term['my_friend']->titleize()->pluralize())),
  'class' => 'activityBox homeRecentList',
) ?>
<?php if (isset($gadget)): ?>
<?php $params['moreInfo'] = array(
  link_to(__('More'), isset($moreUrl) ? $moreUrl : 'friend/showActivity'),
) ?>
<?php endif; ?>
<?php op_include_box($id, get_slot('activities'), $params) ?>
