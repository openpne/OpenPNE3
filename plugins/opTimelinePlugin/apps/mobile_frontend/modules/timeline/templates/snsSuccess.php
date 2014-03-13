<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager); ?>
</center>

<?php $list = array() ?>
<?php if (isset($form)): ?>
<?php slot('activity_form') ?>
<?php echo $form->renderFormTag(url_for('member/updateActivity')) ?>
<?php echo __('Public flag') ?><?php echo $form['public_flag'] ?><br>
<?php echo $form['body'] ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('%post_activity%') ?>">
</form>
<?php end_slot() ?>
<?php $list[] = get_slot('activity_form') ?>
<?php endif; ?>

<?php foreach ($pager->getResults() as $activity): ?>
<?php $list[] = get_partial('timeline/timelineRecord', array('activity' => $activity)) ?>
<?php endforeach; ?>

<?php $params = array(
  'title' => isset($title) ? $title : 'SNSﾒﾝﾊﾞｰ全員の'.$op_term['activity'],
  'list' => $list,
  'border' => true,
) ?>
<?php op_include_parts('list', 'ActivityBox', $params) ?>

<?php op_include_pager_navigation($pager, '@sns_timeline?page=%d', array('is_total' => false, 'use_current_query_string' => true)) ?>
<?php endif; ?>
