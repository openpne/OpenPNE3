<?php op_include_parts('memberImageUploadBox', 'memberImageUploadBox', array(
  'title'  => __('Edit Photo'),
  'images' => $sf_user->getMember()->getMemberImages(),
  'form'   => $form,
)); ?>
