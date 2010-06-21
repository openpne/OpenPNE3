<?php $list = array() ?>
<?php if (isset($form)): ?>
<?php slot('activity_form') ?>
<?php echo $form->renderFormTag(url_for('member/updateActivity')) ?>
<?php echo __('Public flag') ?><?php echo $form['public_flag'] ?><br />
<?php echo $form['body'] ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('%post_activity%') ?>" />
</form>
<?php end_slot() ?>
<?php $list[] = get_slot('activity_form') ?>
<?php endif; ?>
<?php foreach ($activities as $activity): ?>
<?php $list[] = get_partial('default/activityRecord', array('activity' => $activity)) ?>
<?php endforeach; ?>

<?php $params = array(
  'title' => isset($title) ? $title : __('%activity% of %my_friend%', array(
    '%activity%' => $op_term['activity']->titleize()->pluralize(),
    '%my_friend%' => $op_term['my_friend']->titleize()->pluralize(),
  )),
  'list' => $list,
  'border' => true,
) ?>
<?php if (isset($gadget)): ?>
<?php $params['moreInfo'] = array(
  link_to(__('More'), isset($moreUrl) ? $moreUrl : 'friend/showActivity'),
) ?>
<?php endif; ?>
<?php op_include_parts('list', 'ActivityBox', $params) ?>
