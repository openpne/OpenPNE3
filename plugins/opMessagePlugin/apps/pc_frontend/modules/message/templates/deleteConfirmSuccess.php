<?php decorate_with('layoutC') ?>
<div id="formMessageDelete" class="dparts box">
<div class="parts">
<div class="partsHeading">
<h3><?php echo __('Delete this message') ?></h3>
</div>
<div class="block">
<p><?php echo __('Do you delete this message?') ?></p>
<?php echo $form->renderFormTag(url_for($deleteButton)); ?>
<?php echo $form ?>
<div class="operation">
<ul class="moreInfo button">
<li>
<input class="input_submit" type="submit" value="<?php echo __('Delete') ?>" />
</li>
</ul>
</div>
</form>
</div>
</div></div>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
