<?php op_mobile_page_title($member->getName(), __('Add friends')); ?>
<?php echo $form->renderFormTag(url_for('friend/link?id='.$id)) ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Submit') ?>">
</form>
