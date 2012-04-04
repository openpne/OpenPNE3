<?php slot('body') ?>
<div class="body">
<p>
<?php echo __('You can provide this API key to third party applications.') ?>
</p>
<div class="apiKey"><?php echo $apiKey ?></div>
<p>
<?php echo link_to(__('Reset API key'), '@member_config_jsonapi?reset_api_key=1', array('method' => 'post')) ?>
</p>
</div>
<?php end_slot() ?>
<?php op_include_box('memberConfigJsonApiBox', get_slot('body'), array(
  'title'  => __('API Key'),
)); ?>
