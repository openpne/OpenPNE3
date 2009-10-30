<?php if ($category): ?>
<?php echo op_mobile_page_title(__('Confirmation List'), __($config[$category])) ?>
<?php else: ?>
<?php echo op_mobile_page_title(__('Confirmation List')) ?>
<?php endif; ?>

<?php $list_html = array(); ?>
<?php if (count($list)): ?>
<?php echo __('You have the following pending requests. Select "Accept" or "Reject".') ?><br>
<br>
<?php foreach ($list as $item): ?>
<?php slot('_list_html'); ?>
<?php foreach ($item['list'] as $k => $v): ?>
<?php echo __($k) ?>:<br>
<?php if (isset($v['link'])): ?>
<?php echo link_to($v['text'], $v['link']) ?>
<?php else: ?>
<?php echo $v['text'] ?>
<?php endif; ?>
<br>
<?php endforeach; ?>
<?php echo $form->renderFormTag(url_for('@confirmation_decision?id='.$item['id'].'&category='.$category)) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" name="accept" value="<?php echo __('Accept') ?>" class="input_submit" />
<input type="submit" value="<?php echo __('Reject') ?>" class="input_submit" />
</form>
<?php end_slot(); ?>
<?php $list_html[] = get_slot('_list_html'); ?>
<?php endforeach; ?>
<?php op_include_list('confirmList', $list_html, array('border' => true)); ?>

<?php else: ?>
<?php echo __('You don\'t have any pending requests', array('title' => __($config[$category]))) ?>
<?php endif; ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php foreach ($config as $k => $v): ?>
<?php echo link_to(__($v), '@confirmation_list?category='.$k); ?><br>
<?php endforeach; ?>

