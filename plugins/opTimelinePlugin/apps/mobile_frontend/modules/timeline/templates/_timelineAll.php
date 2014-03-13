<?php $list = array() ?>
<?php if (isset($form)): ?>
<?php slot('activity_form') ?>
<?php echo $form->renderFormTag(url_for('member/updateActivity')) ?>
<?php echo __('Public flag') ?><?php echo $form['public_flag'] ?><br>
<?php echo $form['body'] ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('%post_activity%') ?>" />
</form>
<?php end_slot() ?>
<?php $list[] = get_slot('activity_form') ?>
<?php endif; ?>

<?php $ac = array() ?>
<?php foreach ($activities as $activity): ?>
<?php $list[] = get_partial('timeline/timelineRecord', array('activity' => $activity)) ?>
<?php endforeach; ?>

<?php $params = array(
  'title' => isset($title) ? $title : 'SNSﾒﾝﾊﾞｰ全員の'.$op_term['activity'],
  'list' => $list,
  'border' => true,
) ?>
<?php if (isset($gadget)): ?>
<?php $params['moreInfo'] = array(
  link_to(__('More'), isset($moreUrl) ? $moreUrl : '@sns_timeline'),
) ?>
<?php endif; ?>
<?php op_include_parts('list', 'ActivityBox', $params) ?>
