<?php op_include_parts('memberImagesBox', 'memberImagesBox', array(
    'title' => __("%1%'s Photos", array('%1%' => $member->getName())),
    'images' => $member->getMemberImage()
  ))
?>
