<?php include_parts('memberImageUploadBox', 'memberImageUploadBox', array(
  'images' => $sf_user->getMember()->getMemberImages(),
  'form'   => $form,
)); ?>
