<?php
$form = new BaseForm();
op_include_parts('yesNo', 'deleteConfirmForm', array(
  'title' => __('Do you delete this %community%?'),
  'yes_form' => '<input type="hidden" name="is_delete"/>'
              . '<input type="hidden" name="'.$form->getCSRFFieldName().'" value="'.$form->getCSRFToken().'"/>',
  'button' => __('Delete'),
))
?>
