<?php op_include_parts('memberImageUploadBox', 'memberImageUploadBox', array(
  'title'  => __('写真を編集する'),
  'images' => $sf_user->getMember()->getMemberImages(),
  'form'   => $form,
)); ?>
