<?php $options = array(
  'title' => __('Add a new App'),
  'url' => url_for('@application_install'),
  'button' => __('Add'),
); ?>
<?php if ($rule == ApplicationTable::ADD_APPLICATION_NECESSARY_TO_PERMIT): ?>
<?php $options['body'] =  __("If the members use the app, the SNS administrator's permission is necessary.") ?>
<?php endif; ?>
<?php op_include_form('form', $form ,$options) ?>
