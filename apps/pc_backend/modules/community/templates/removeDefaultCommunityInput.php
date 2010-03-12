<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('Remove default community')); ?>

<p><?php echo __('Do you remove this community from default?') ?></p>

<?php $form = new BaseForm() ?>
<?php echo $form->renderFormTag(url_for('community/removeDefaultCommunity?id='.$community->getId())) ?>
<?php include_partial('community/communityInfo', array(
  'community' => $community,
  'moreInfo' => array('<input type="submit" value="'.__('Yes').'" />')
)); ?>
<?php echo $form->renderHiddenFields() ?>
</form>
