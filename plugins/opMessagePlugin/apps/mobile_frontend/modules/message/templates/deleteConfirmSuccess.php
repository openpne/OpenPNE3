<?php
op_mobile_page_title(__('Delete this message'));
?>
<?php echo __('Do you delete this message?') ?><br /><br />
<?php echo $form->renderFormTag(url_for($deleteButton)); ?>
<?php echo $form ?>
<center>
<input type="submit" value="<?php echo __('Delete') ?>"  />
</center>
</form>

