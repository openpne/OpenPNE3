<hr color="<?php echo $op_color['core_color_11'] ?>">
<?php include_partial('timeline/timelineRecord', array('activity' => $activity, 'isOperation' => false)) ?>
<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php $list = array() ?>
<?php $replies = $activity->getReplies() ?>
<?php if (FALSE !== $replies): ?>
<?php foreach ($replies as $reply): ?>
<?php $list[] = get_partial('timeline/timelineComment', array('activity' => $reply)) ?>
<?php endforeach; ?>
<?php endif; ?>

<?php if (isset($form)): ?>
<?php slot('activity_form') ?>
<?php echo $form->renderFormTag(url_for('@update_timeline')) ?>
<?php echo $form['body'] ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo 'ｺﾒﾝﾄする' ?>">
</form>
<?php end_slot() ?>
<?php $list[] = get_slot('activity_form') ?>
<?php endif; ?>
<?php $params = array(
  'title' => isset($title) ? $title : 'ｺﾒﾝﾄ',
  'list' => $list,
  'border' => true,
) ?>
<?php op_include_parts('list', 'ActivityBox', $params) ?>
