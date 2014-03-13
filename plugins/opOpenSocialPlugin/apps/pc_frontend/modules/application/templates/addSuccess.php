<?php slot('box_body') ?>
<div class="applicationInfoBox">
<div class="applicationThumbnail">
<?php if ($application->getThumbnail()) : ?>
<?php echo image_tag($application->getThumbnail(), array('alt' => $application->getTitle())) ?>
<?php else : ?>
<?php echo image_tag('no_image.gif', array('size' => '76x76')) ?>
<?php endif; ?>
</div>
<div class="info">
<?php echo __('Do you wish to install this App?') ?><br> 
<?php echo __('The App might use your profile and your %friend% information.', array('%friend%' => $op_term['friend']->pluralize())) ?>
</div>
</div>
<div style="clear: both;">&nbsp;</div>
<?php end_slot() ?>

<?php op_include_parts('yesNo', 'AddApplicationBox', array(
  'title'      => __('Add App: %0%', array('%0%' => $application->getTitle())), 
  'body'       => get_slot('box_body'),
  'yes_form'   => new sfForm(),
  'yes_method' => 'post',
  'no_url'     => url_for('@application_gallery'),
)) ?>
