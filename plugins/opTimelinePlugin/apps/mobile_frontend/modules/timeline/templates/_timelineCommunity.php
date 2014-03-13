<?php if (isset($form)): ?>
<?php $list = array() ?>
<?php slot('activity_form') ?>
<?php echo $form->renderFormTag(url_for('@update_timeline')) ?>
<?php echo $form['body'] ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('%post_activity%') ?>">
</form>
<?php end_slot() ?>
<?php $list[] = get_slot('activity_form') ?>

<?php foreach ($activities as $activity): ?>
<?php $list[] = get_partial('timeline/timelineRecord', array('activity' => $activity)) ?>
<?php endforeach; ?>

<?php $params = array(
  'title' => isset($title) ? $title : $community->getName().'の'.$op_term['activity'],
  'list' => $list,
  'border' => true,
) ?>
<?php op_include_parts('list', 'ActivityBox', $params) ?>
<?php endif; ?>
