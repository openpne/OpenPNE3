<div class="row">
  <div class="gadget_header span12"><?php echo __('Quit "%1%"', array('%1%' => $community->getName())) ?></div>
</div>

<div class="row">
  <div class="span4"><?php echo link_to(op_image_tag_sf_image($community->getImageFileName(), array('size' => '48x48')), '@community_home?id='.$id) ?></div>
  <div class="span8"><?php echo link_to($community->getName(), '@community_home?id='.$id) ?></div>
  <div class="span12 center"><?php echo __('Do you really quit the following %community%?') ?></div>
  <div class="span12 center">
    <div class="row">
    <?php echo form_tag($sf_request->getCurrentUri()); ?>
    <?php foreach ($form as $field): ?>
    <?php if (!$field->isHidden()): ?>
      <div class="control-group<?php echo $field->hasError()? ' error' : '' ?>">
        <label class="control-label"><?php echo $field->renderLabel() ?></label>
        <div class="controls">
        <?php if ($field->hasError()): ?>
        <span class="label label-important label-block"><?php echo __($field->renderError()); ?></span>
        <?php endif ?>
        <?php echo $field->render(array('class' => 'span12')) ?>
        <span class="help-block"><?php echo $field->renderHelp(); ?></span>    
        </div>
      </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <input type="submit" name="submit" value="<?php echo __('Send') ?>" class="btn btn-danger" />
    <?php echo $form->renderHiddenFields(); ?>
    </form>
    </div>
  </div>
</div>
