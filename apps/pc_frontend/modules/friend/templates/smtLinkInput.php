<div class="row">
  <div class="gadget_header span12"><?php echo __('Add %my_friend%', array('%my_friend%' => $op_term['my_friend']->pluralize())) ?></div>
</div>

<div class="row">
  <div class="span4"><?php echo link_to(op_image_tag_sf_image($member->getImageFileName(), array('size' => '48x48')), '@member_profile?id='.$id) ?></div>
  <div class="span8"><?php echo link_to($member->getName(), '@member_profile?id='.$id) ?></div>
  <div class="span12">
    <?php echo form_tag($sf_request->getCurrentUri()); ?>
    <?php foreach ($form as $field): ?>
    <?php if (!$field->isHidden()): ?>
    <div class="clearfix <?php $field->hasError() ? 'error' : '' ?>">
    <label for="xlInput3" class="span12"><?php echo $field->renderLabel() ?></label>
    <?php echo $field->render(array('class' => 'span12')) ?>
    <span class="help-block"><?php echo $field->renderHelp() ?></span>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <input type="submit" name="submit" value="<?php echo __('Send') ?>" class="btn btn-primary" />
    <?php echo $form->renderHiddenFields(); ?>
    </form>
  </div>
</div>
