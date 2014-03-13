<?php op_mobile_page_title(__($title), __('Message List')) ?>
<?php if ($pager->getNbResults()): ?>
<center><?php op_include_pager_total($pager) ?></center>
<?php if ($form->hasGlobalErrors()): ?>
<font color="<?php echo $op_color["core_color_22"] ?>"><?php echo $form->renderGlobalErrors() ?></font>
<?php endif; ?>
<form action="<?php echo url_for('@'.$messageType.'List') ?>" method="post">
<?php echo $form->renderHiddenFields(); ?>
<?php $_list = array() ?>
<?php foreach ($pager->getResults() as $message): ?>
<?php $_list[] = $form['message_ids['.$message->getId().']']->render().
get_partial($messageType.'ListRecord', array('message' => $message)); ?>
<?php endforeach; ?>
<?php op_include_list('messageList', $_list, array()); ?>
<?php if ('dust' == $messageType): ?>
<input type="submit" name="restore" value="<?php echo __('Restore') ?>"><br>
<?php endif; ?>
<input type="submit" value="<?php echo __('Delete') ?>">
</form>
<center><?php op_include_pager_navigation($pager, '@'.$messageType.'List?page=%d', array('is_total' => false)) ?></center>
<?php else: ?>
<?php echo __('There are no messages.') ?><br><br>
<?php endif; ?>
<hr color="<?php echo $op_color['core_color_11'] ?>">
<?php include_partial('message/menu', array('messageType' => $messageType)) ?>
