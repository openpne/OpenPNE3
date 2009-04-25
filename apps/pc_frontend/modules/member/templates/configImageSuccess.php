<?php op_include_parts('memberImagesBox', 'memberImageUploadBox', array(
  'title'  => __('Edit Photo'),
  'images' => $sf_user->getMember()->getMemberImage(),
  'form'   => $form,
)); ?>
